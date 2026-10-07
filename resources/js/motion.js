/* ============================================================================
   MOTION ENGINE — Kıbrıs Web Tasarımcı
   ----------------------------------------------------------------------------
   Sıfır bağımlılık. Harici animasyon kütüphanesi YOK.
   Tek bir requestAnimationFrame döngüsü, ölçümler önbellekli (layout thrash yok),
   yalnızca transform/opacity yazılır.

   Modüller
     1  Ortam & Ticker
     2  Scroll durumu (konum, hız, yön)
     3  Ölçüm kayıt defteri (scroll'a bağlı ilerleme)
     4  Metin bölme (kelime / karakter maskesi)
     5  Reveal (IntersectionObserver)
     6  Scroll'a bağlı: parallax, yatay pin, dev yazı, ilerleme çubukları
     7  Sayaçlar
     8  Marquee (scroll hızına tepki verir)
     9  Magnetic öğeler
    10  Özel imleç
    11  Liste üstü gezinen görsel (hover follower)
    12  Sayfa geçiş perdesi
    13  Header davranışı
    14  Kıbrıs saati
   ========================================================================== */

/* ── 1 ─ Ortam & Ticker ──────────────────────────────────────────────────── */

const mqFine = matchMedia('(hover: hover) and (pointer: fine)');
const mqReduced = matchMedia('(prefers-reduced-motion: reduce)');

const env = {
  get fine() { return mqFine.matches; },
  get reduced() { return mqReduced.matches; },
};

const clamp = (v, a = 0, b = 1) => (v < a ? a : v > b ? b : v);
const lerp = (a, b, t) => a + (b - a) * t;
/** Yumuşak giriş/çıkış — CSS'teki cubic-bezier(.7,0,.3,1) hissi. */
const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
/** Sert çıkış — expo out. Kooba'nın "fırlayıp yerine oturan" hissi. */
const easeOut = (t) => (t >= 1 ? 1 : 1 - Math.pow(2, -10 * t));

const ticker = {
  subs: new Set(),
  running: false,
  add(fn) {
    this.subs.add(fn);
    if (!this.running) { this.running = true; requestAnimationFrame(this.loop); }
    return fn;
  },
  remove(fn) { this.subs.delete(fn); },
  loop: null,
};

ticker.loop = function loop() {
  scroll.sample();
  ticker.subs.forEach((fn) => fn(scroll));
  requestAnimationFrame(ticker.loop);
};

/* ── 2 ─ Scroll durumu ───────────────────────────────────────────────────── */

const scroll = {
  y: 0,          // ham scroll konumu
  prev: 0,
  delta: 0,      // bu karedeki fark
  velocity: 0,   // yumuşatılmış hız (px/kare)
  direction: 1,  // 1 aşağı, -1 yukarı
  vh: 0,
  vw: 0,
  progress: 0,   // sayfa ilerlemesi 0→1

  sample() {
    this.prev = this.y;
    this.y = window.scrollY || document.documentElement.scrollTop || 0;
    this.delta = this.y - this.prev;
    // Hız yumuşatma: ani sıçramaları törpüle, duruşta sıfıra dön.
    this.velocity = lerp(this.velocity, this.delta, 0.14);
    if (Math.abs(this.velocity) < 0.01) this.velocity = 0;
    if (this.delta !== 0) this.direction = this.delta > 0 ? 1 : -1;
    const max = document.documentElement.scrollHeight - this.vh;
    this.progress = max > 0 ? clamp(this.y / max) : 0;
  },

  measure() {
    this.vh = window.innerHeight;
    this.vw = window.innerWidth;
  },
};

/* ── 3 ─ Ölçüm kayıt defteri ─────────────────────────────────────────────── */
/*
   Her karede getBoundingClientRect() çağırmak zorunlu layout hesabı doğurur.
   Bunun yerine öğenin belge içindeki konumunu bir kez ölçüp saklıyoruz;
   kare içinde yalnızca aritmetik yapılıyor. Yeniden ölçüm: resize, yazı tipi
   yüklenmesi ve ResizeObserver tetiklemeleri.
*/

/** @type {{el:HTMLElement, top:number, height:number, mode:string, apply:Function}[]} */
const tracked = [];
let measureQueued = false;

function track(el, mode, apply) {
  const item = { el, top: 0, height: 0, mode, apply };
  tracked.push(item);
  measureItem(item);
  return item;
}

function measureItem(item) {
  const r = item.el.getBoundingClientRect();
  item.top = r.top + window.scrollY;
  item.height = r.height;
}

function measureAll() {
  scroll.measure();
  for (const item of tracked) measureItem(item);
  measureQueued = false;
}

function queueMeasure() {
  if (measureQueued) return;
  measureQueued = true;
  requestAnimationFrame(measureAll);
}

/**
 * Öğenin scroll ilerlemesi. Modlar:
 *   'through' — üstü alt kenara girdiğinde 0, altı üst kenardan çıkarken 1
 *   'enter'   — üstü alt kenara girdiğinde 0, üstü ekranın tepesine geldiğinde 1
 *   'cover'   — altı alt kenara geldiğinde 0, üstü tepeye geldiğinde 1
 *   'pin'     — yüksek sarmalayıcı: yapışkan çocuk ekranda kaldığı süre 0→1
 */
function progressOf(item) {
  const { vh } = scroll;
  const top = item.top - scroll.y;      // viewport'a göre üst kenar
  const h = item.height;

  switch (item.mode) {
    case 'enter':
      return clamp((vh - top) / vh);
    case 'cover':
      return clamp((vh - top) / (vh + h));
    case 'pin': {
      const span = h - vh;
      return span > 0 ? clamp(-top / span) : 0;
    }
    case 'through':
    default:
      return clamp((vh - top) / (vh + h));
  }
}

/* ── 4 ─ Metin bölme ─────────────────────────────────────────────────────── */
/*
   Başlığı kelimelere (istenirse karakterlere) böler; her parça `overflow:hidden`
   bir maskenin içine girer ve aşağıdan yukarı yükselerek belirir.
   İç etiketler (örn. .k-hl vurgusu, <br>) korunur — yalnızca metin düğümleri
   parçalanır, böylece vurgu şeridi ve satır sonları bozulmaz.
*/

let splitUid = 0;

function splitText(el) {
  if (el.dataset.splitDone) return;
  const mode = el.dataset.split === 'chars' ? 'chars' : 'words';
  const step = parseFloat(el.dataset.splitStep || (mode === 'chars' ? 0.022 : 0.055));
  const base = parseFloat(el.dataset.splitDelay || 0);

  const pieces = [];

  const walk = (node) => {
    const children = Array.from(node.childNodes);
    for (const child of children) {
      if (child.nodeType === Node.TEXT_NODE) {
        const text = child.textContent;
        if (!text.trim()) continue;
        const frag = document.createDocumentFragment();
        // Boşlukları koru: ayırıcıyı yakalayan bölme.
        const parts = text.split(/(\s+)/);
        for (const part of parts) {
          if (part === '') continue;
          if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); continue; }
          if (mode === 'chars') {
            // Kelime içi karakterler satır sonunda kopmasın.
            const nowrap = document.createElement('span');
            nowrap.className = 'k-nowrap';
            for (const ch of Array.from(part)) nowrap.appendChild(makeMask(ch, pieces));
            frag.appendChild(nowrap);
          } else {
            frag.appendChild(makeMask(part, pieces));
          }
        }
        node.replaceChild(frag, child);
      } else if (child.nodeType === Node.ELEMENT_NODE) {
        if (child.tagName === 'BR') continue;
        walk(child);
      }
    }
  };

  walk(el);

  pieces.forEach((inner, i) => {
    inner.style.transitionDelay = (base + i * step).toFixed(3) + 's';
  });

  el.classList.add('k-split');
  el.dataset.splitDone = '1';
  el.dataset.splitId = String(++splitUid);
}

function makeMask(text, pieces) {
  const mask = document.createElement('span');
  mask.className = 'k-w';
  const inner = document.createElement('span');
  inner.className = 'k-wi';
  inner.textContent = text;
  mask.appendChild(inner);
  pieces.push(inner);
  return mask;
}

/* ── 5 ─ Reveal ──────────────────────────────────────────────────────────── */

function initReveal() {
  const nodes = document.querySelectorAll('[data-split]');
  if (!env.reduced) nodes.forEach(splitText);

  const targets = document.querySelectorAll('.k-reveal, .k-split, [data-reveal]');

  if (env.reduced || !('IntersectionObserver' in window)) {
    targets.forEach((el) => el.classList.add('is-in'));
    return;
  }

  const io = new IntersectionObserver((entries) => {
    for (const e of entries) {
      if (!e.isIntersecting) continue;
      e.target.classList.add('is-in');
      io.unobserve(e.target);
    }
  }, { threshold: 0.1, rootMargin: '0px 0px -12% 0px' });

  targets.forEach((el) => {
    // İlk ekranda zaten görünen öğeler beklemesin; kısa bir gecikmeyle açılsın
    // ki sayfa yüklenirken hepsi aynı anda "patlamasın".
    if (el.getBoundingClientRect().top < window.innerHeight) {
      requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('is-in')));
    } else {
      io.observe(el);
    }
  });
}

/* ── 6 ─ Scroll'a bağlı efektler ─────────────────────────────────────────── */

function initScrollLinked() {
  if (env.reduced) return;

  /* Parallax — data-parallax="0.18" (negatif değer ters yön) */
  document.querySelectorAll('[data-parallax]').forEach((el) => {
    const amount = parseFloat(el.dataset.parallax) || 0.15;
    const item = track(el, 'through', null);
    item.apply = () => {
      const p = progressOf(item) - 0.5;
      el.style.transform = `translate3d(0, ${(-p * amount * item.height).toFixed(2)}px, 0)`;
    };
  });

  /* Yatay kayan dev arka plan yazısı — data-drift="600" (px) */
  document.querySelectorAll('[data-drift]').forEach((el) => {
    const range = parseFloat(el.dataset.drift) || 600;
    const host = el.closest('[data-drift-host]') || el.parentElement;
    const item = track(host, 'through', null);
    item.apply = () => {
      const p = progressOf(item) - 0.5;
      el.style.transform = `translate3d(${(p * range).toFixed(2)}px, 0, 0)`;
    };
  });

  /* Yapışkan yatay şerit — sarmalayıcı yüksek, çocuk sticky, içerik yatay akar.
     Sarmalayıcı yüksekliği şeridin GERÇEK genişliğinden hesaplanır: dikey yol
     ile yatay yol 1:1 eşleşir, sonda ölü boşluk ya da yarım kalan şerit olmaz.
     Mobilde devre dışı — orada doğal yatay kaydırma kullanılır. */
  document.querySelectorAll('[data-hscroll]').forEach((wrap) => {
    const rail = wrap.querySelector('[data-hscroll-rail]');
    const pane = wrap.querySelector('[data-hscroll-pane]');
    if (!rail || !pane) return;

    let dist = 0;

    const resize = () => {
      if (scroll.vw < 900) {
        wrap.style.height = '';
        rail.style.transform = '';
        dist = 0;
        return;
      }
      dist = Math.max(0, rail.scrollWidth - scroll.vw + 48);
      wrap.style.height = (pane.offsetHeight + dist) + 'px';
    };

    resize();
    window.addEventListener('resize', resize);

    const item = track(wrap, 'pin', null);
    item.apply = () => {
      if (!dist) return;
      rail.style.transform = `translate3d(${(-progressOf(item) * dist).toFixed(2)}px, 0, 0)`;
    };
  });

  /* Ölçek/clip ile açılan görseller — data-unmask */
  document.querySelectorAll('[data-unmask]').forEach((el) => {
    const item = track(el, 'cover', null);
    const img = el.querySelector('img, [data-unmask-inner]');
    item.apply = () => {
      const p = clamp((progressOf(item) - 0.05) / 0.5);
      const e = easeOut(p);
      el.style.clipPath = `inset(${((1 - e) * 50).toFixed(2)}% 0% 0% 0%)`;
      if (img) img.style.transform = `scale(${(1.22 - 0.22 * e).toFixed(4)})`;
    };
  });

  /* Scroll ilerleme çubuğu */
  const bar = document.getElementById('k-progress');
  if (bar) ticker.add(() => { bar.style.transform = `scaleX(${scroll.progress.toFixed(4)})`; });

  /* Yapışkan üst üste binen kartlar — data-stack içindeki [data-stack-item] */
  document.querySelectorAll('[data-stack]').forEach((wrap) => {
    const items = Array.from(wrap.querySelectorAll('[data-stack-item]'));
    if (!items.length) return;
    const rec = track(wrap, 'pin', null);
    rec.apply = () => {
      const p = progressOf(rec) * (items.length - 1);
      // Efekt her ekranda çalışır. Dar ekranda kartlar zaten birbirine yakın
      // durduğu için derinliği azaltıyoruz — aynı değerler telefonda kartları
      // gereğinden fazla küçültüp okunaksız yapıyor.
      const narrow = scroll.vw < 768;
      const depth = narrow ? 0.028 : 0.045;
      const lift = narrow ? 6 : 10;
      items.forEach((card, i) => {
        const local = clamp(p - i);          // bu kart ne kadar "geride kaldı"
        // Yalnızca ölçek: opaklık düşürmek üstteki kartı saydamlaştırıp
        // altındaki kartın yazısını sızdırıyor. Kartlar opak kalmalı.
        card.style.transform = `translate3d(0, ${(-local * lift).toFixed(1)}px, 0) scale(${(1 - local * depth).toFixed(4)})`;
      });
    };
  });

  if (tracked.length) {
    ticker.add(() => { for (const item of tracked) if (item.apply) item.apply(); });
  }
}

/* ── 7 ─ Sayaçlar ────────────────────────────────────────────────────────── */

function initCounters() {
  const nodes = document.querySelectorAll('[data-count]');
  if (!nodes.length) return;

  const run = (el) => {
    const target = parseFloat(el.dataset.count) || 0;
    const decimals = parseInt(el.dataset.countDecimals || '0', 10);
    const dur = parseInt(el.dataset.countDuration || '1600', 10);
    if (env.reduced) { el.textContent = target.toFixed(decimals); return; }
    const start = performance.now();
    const step = (now) => {
      const p = clamp((now - start) / dur);
      el.textContent = (target * easeOut(p)).toFixed(decimals);
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = target.toFixed(decimals);
    };
    requestAnimationFrame(step);
  };

  if (!('IntersectionObserver' in window)) { nodes.forEach(run); return; }
  const io = new IntersectionObserver((entries) => {
    for (const e of entries) {
      if (!e.isIntersecting) continue;
      run(e.target);
      io.unobserve(e.target);
    }
  }, { threshold: 0.5 });
  nodes.forEach((el) => io.observe(el));
}

/* ── 8 ─ Marquee ─────────────────────────────────────────────────────────── */
/*
   CSS keyframes yerine rAF: şerit scroll hızına tepki verir, yön scroll yönüyle
   birlikte döner ve hızlı kaydırmada hafifçe eğilir (skew). Kütüphane yok.
*/

function initMarquee() {
  document.querySelectorAll('[data-marquee]').forEach((root) => {
    const rail = root.querySelector('[data-marquee-rail]');
    if (!rail) return;

    const speed = parseFloat(root.dataset.marquee) || 0.6;   // px/kare temel hız
    const reactive = root.dataset.marqueeReactive !== 'off';
    const original = rail.innerHTML;
    let width = 0;
    let offset = 0;

    const fill = () => {
      rail.innerHTML = original;
      const one = rail.scrollWidth;
      if (!one) return;
      // Ekranı iki kez dolduracak kadar çoğalt — kesintisiz döngü.
      const copies = Math.ceil((scroll.vw * 2) / one) + 1;
      let html = '';
      for (let i = 0; i < copies; i++) html += original;
      rail.innerHTML = html;
      width = rail.scrollWidth / copies;
    };

    fill();
    window.addEventListener('resize', fill);

    if (env.reduced) return;

    ticker.add(() => {
      if (!width) return;
      const boost = reactive ? scroll.velocity * 0.55 : 0;
      offset -= speed + boost;
      // Sonsuz sarma
      if (offset <= -width) offset += width;
      if (offset > 0) offset -= width;
      const skew = reactive ? clamp(scroll.velocity * 0.14, -7, 7) : 0;
      rail.style.transform = `translate3d(${offset.toFixed(2)}px, 0, 0) skewX(${skew.toFixed(2)}deg)`;
    });
  });
}

/* ── 8b ─ Nokta alanı (hero arka planı) ──────────────────────────────────── */
/*
   Canvas'a elle çizilen nokta ızgarası. İki hareket kaynağı var:
   yavaş bir dalga (imleç olmasa da yaşıyor) ve imlecin yakınında büyüyüp
   brand rengine dönen noktalar. Kütüphane yok, tek rAF döngüsüne biniyor.
*/

function initDotField() {
  const host = document.querySelector('[data-dots]');
  if (!host) return;

  const canvas = document.createElement('canvas');
  canvas.className = 'k-dots';
  canvas.setAttribute('aria-hidden', 'true');
  host.prepend(canvas);

  const ctx = canvas.getContext('2d');
  const GAP = 34;          // noktalar arası mesafe
  const RADIUS = 130;      // imlecin etki yarıçapı
  let w = 0, h = 0, cols = 0, rows = 0, offsetX = 0, offsetY = 0;
  let mx = -9999, my = -9999, tx = -9999, ty = -9999;
  let t = 0;

  const resize = () => {
    const rect = host.getBoundingClientRect();
    const dpr = Math.min(devicePixelRatio || 1, 2);
    w = rect.width;
    h = rect.height;
    canvas.width = Math.round(w * dpr);
    canvas.height = Math.round(h * dpr);
    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    cols = Math.ceil(w / GAP) + 1;
    rows = Math.ceil(h / GAP) + 1;
    // Izgarayı ortala ki kenarlarda yarım sıra kalmasın.
    offsetX = (w - (cols - 1) * GAP) / 2;
    offsetY = (h - (rows - 1) * GAP) / 2;
  };

  const draw = () => {
    ctx.clearRect(0, 0, w, h);

    for (let i = 0; i < cols; i++) {
      for (let j = 0; j < rows; j++) {
        const x = offsetX + i * GAP;
        const y = offsetY + j * GAP;

        // İki çapraz dalga üst üste — düzenli nabız yerine yavaşça kayan bir örüntü.
        const wave = (Math.sin(i * 0.28 + t) + Math.sin(j * 0.34 - t * 0.7)) * 0.25 + 0.5;

        const dx = x - mx;
        const dy = y - my;
        const dist = Math.hypot(dx, dy);
        const near = dist < RADIUS ? 1 - dist / RADIUS : 0;
        const pull = near * near;   // yumuşak düşüş

        const r = 0.7 + wave * 1.1 + pull * 2.4;
        const alpha = 0.06 + wave * 0.14 + pull * 0.5;

        ctx.beginPath();
        // İmleç yakınındaki noktalar dalgayla birlikte hafifçe kayar.
        ctx.arc(x + (dx / (dist || 1)) * pull * 6, y + (dy / (dist || 1)) * pull * 6, r, 0, Math.PI * 2);
        ctx.fillStyle = pull > 0.12
          ? `rgba(227, 6, 19, ${alpha.toFixed(3)})`
          : `rgba(15, 15, 15, ${alpha.toFixed(3)})`;
        ctx.fill();
      }
    }
  };

  resize();
  window.addEventListener('resize', resize);

  if (env.reduced) { draw(); return; }

  if (env.fine) {
    host.addEventListener('pointermove', (e) => {
      const rect = host.getBoundingClientRect();
      tx = e.clientX - rect.left;
      ty = e.clientY - rect.top;
    });
    host.addEventListener('pointerleave', () => { tx = -9999; ty = -9999; });
  }

  ticker.add(() => {
    // Ekrandan çıkınca boşuna çizme.
    if (host.getBoundingClientRect().bottom < 0) return;
    t += 0.012;
    mx = lerp(mx, tx, 0.1);
    my = lerp(my, ty, 0.1);
    draw();
  });
}

/* ── 9 ─ Magnetic öğeler ─────────────────────────────────────────────────── */

function initMagnetic() {
  if (!env.fine || env.reduced) return;

  document.querySelectorAll('[data-magnetic]').forEach((el) => {
    const strength = parseFloat(el.dataset.magnetic) || 0.32;
    const label = el.querySelector('[data-magnetic-label]');
    let tx = 0, ty = 0, cx = 0, cy = 0, active = false, sub = null;

    const frame = () => {
      cx = lerp(cx, tx, 0.16);
      cy = lerp(cy, ty, 0.16);
      el.style.transform = `translate3d(${cx.toFixed(2)}px, ${cy.toFixed(2)}px, 0)`;
      if (label) label.style.transform = `translate3d(${(cx * 0.35).toFixed(2)}px, ${(cy * 0.35).toFixed(2)}px, 0)`;
      if (!active && Math.abs(cx) < 0.05 && Math.abs(cy) < 0.05) {
        el.style.transform = '';
        if (label) label.style.transform = '';
        ticker.remove(sub); sub = null;
      }
    };

    const start = () => { if (!sub) sub = ticker.add(frame); };

    el.addEventListener('pointerenter', () => { active = true; start(); });
    el.addEventListener('pointermove', (e) => {
      const r = el.getBoundingClientRect();
      tx = (e.clientX - (r.left + r.width / 2)) * strength;
      ty = (e.clientY - (r.top + r.height / 2)) * strength;
    });
    el.addEventListener('pointerleave', () => { active = false; tx = 0; ty = 0; start(); });
  });
}

/* ── 10 ─ Özel imleç ─────────────────────────────────────────────────────── */

function initCursor() {
  const cursor = document.getElementById('k-cursor');
  if (!cursor || !env.fine || env.reduced) { if (cursor) cursor.remove(); return; }

  const dot = cursor.querySelector('[data-cursor-dot]');
  const label = cursor.querySelector('[data-cursor-label]');
  let x = scroll.vw / 2, y = scroll.vh / 2, cx = x, cy = y, dx = x, dy = y;

  // İmleç mürekkep renkli; #0F0F0F bölümlerin üstünde zemine karışıp
  // kayboluyordu. Altındaki bölümün tonunu izleyip koyuda beyaza dönüyoruz.
  const DARK = '.k-dark, .k-menu, [data-cursor-dark]';
  let toneY = -1, toneFrame = 0;

  const syncTone = (el) => {
    if (!el || !el.closest) return;
    cursor.classList.toggle('is-dark', !!el.closest(DARK));
  };

  window.addEventListener('pointermove', (e) => {
    x = e.clientX; y = e.clientY;
    if (!cursor.classList.contains('is-live')) cursor.classList.add('is-live');
  }, { passive: true });

  document.addEventListener('pointerleave', () => cursor.classList.remove('is-live'));

  ticker.add(() => {
    cx = lerp(cx, x, 0.18); cy = lerp(cy, y, 0.18);
    dx = lerp(dx, x, 0.42); dy = lerp(dy, y, 0.42);
    cursor.style.transform = `translate3d(${cx.toFixed(2)}px, ${cy.toFixed(2)}px, 0) translate(-50%, -50%)`;
    if (dot) dot.style.transform = `translate3d(${(dx - cx).toFixed(2)}px, ${(dy - cy).toFixed(2)}px, 0) translate(-50%, -50%)`;

    // Fare hiç kımıldamasa da kaydırma sırasında altındaki bölüm değişir.
    // elementFromPoint yerleşimi zorladığı için yalnızca kaydırma varken ve
    // altı karede bir bakıyoruz — göz için anlık, kare bütçesi için ucuz.
    if (scroll.y !== toneY && ++toneFrame % 6 === 0) {
      toneY = scroll.y;
      syncTone(document.elementFromPoint(x, y));
    }
  });

  const HOVER = 'a, button, [role="button"], input, textarea, select, summary, [data-hover]';
  const CTA = '[data-cursor="cta"]';
  const DRAG = '[data-cursor="drag"]';

  document.addEventListener('pointerover', (e) => {
    const cta = e.target.closest(CTA);
    const drag = e.target.closest(DRAG);
    const hov = e.target.closest(HOVER);
    cursor.classList.toggle('is-cta', !!cta);
    cursor.classList.toggle('is-drag', !!drag && !cta);
    cursor.classList.toggle('is-hover', !!hov && !cta && !drag);
    const src = cta || drag || hov;
    if (label) label.textContent = (src && src.dataset.cursorLabel) || '';
    cursor.classList.toggle('has-label', !!(src && src.dataset.cursorLabel));
    syncTone(e.target);
  });

  document.addEventListener('pointerout', (e) => {
    if (e.relatedTarget && e.relatedTarget.closest && e.relatedTarget.closest(HOVER + ',' + CTA + ',' + DRAG)) return;
    cursor.classList.remove('is-hover', 'is-cta', 'is-drag', 'has-label');
    if (label) label.textContent = '';
  });

  document.addEventListener('pointerdown', () => cursor.classList.add('is-down'));
  document.addEventListener('pointerup', () => cursor.classList.remove('is-down'));
}

/* ── 11 ─ Liste üstü gezinen görsel ──────────────────────────────────────── */
/*
   Hizmet/iş satırlarının üzerine gelince imleci takip eden bir önizleme kartı
   belirir. Ajans sitelerinin imza hareketi — burada elle yazıldı.
*/

function initHoverFollower() {
  const host = document.querySelector('[data-follower]');
  if (!host || !env.fine || env.reduced) return;

  const card = host.querySelector('[data-follower-card]');
  const rows = Array.from(host.querySelectorAll('[data-follower-row]'));
  if (!card || !rows.length) return;

  const slides = Array.from(card.querySelectorAll('[data-follower-slide]'));
  let x = 0, y = 0, cx = 0, cy = 0, visible = false, sub = null, lastY = 0;

  const frame = () => {
    cx = lerp(cx, x, 0.13);
    cy = lerp(cy, y, 0.13);
    const tilt = clamp((y - lastY) * 0.6, -12, 12);
    lastY = lerp(lastY, y, 0.13);
    card.style.transform = `translate3d(${cx.toFixed(2)}px, ${cy.toFixed(2)}px, 0) translate(-50%, -50%) rotate(${tilt.toFixed(2)}deg)`;
    if (!visible && Math.abs(cx - x) < 0.5 && Math.abs(cy - y) < 0.5) { ticker.remove(sub); sub = null; }
  };

  const wake = () => { if (!sub) sub = ticker.add(frame); };

  host.addEventListener('pointermove', (e) => { x = e.clientX; y = e.clientY; wake(); });

  rows.forEach((row, i) => {
    row.addEventListener('pointerenter', () => {
      visible = true;
      card.classList.add('is-on');
      slides.forEach((s, si) => s.classList.toggle('is-on', si === i % slides.length));
      wake();
    });
    row.addEventListener('pointerleave', () => {
      visible = false;
      card.classList.remove('is-on');
      wake();
    });
  });
}

/* ── 12 ─ Sayfa geçiş perdesi ────────────────────────────────────────────── */

function initPageTransition() {
  const curtain = document.getElementById('k-curtain');
  if (!curtain || env.reduced) return;

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[href]');
    if (!a || a.dataset.noTransition !== undefined) return;
    const href = a.getAttribute('href');
    if (!href) return;
    if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return;
    if (a.target === '_blank' || a.hasAttribute('download')) return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
    let url;
    try { url = new URL(href, location.href); } catch { return; }
    if (url.origin !== location.origin) return;
    if (url.pathname === location.pathname && url.search === location.search) return;

    e.preventDefault();
    curtain.classList.add('is-cover');
    setTimeout(() => { location.href = href; }, 420);
  });

  // Geri tuşuyla dönüldüğünde perde açık kalmasın (bfcache).
  window.addEventListener('pageshow', (e) => { if (e.persisted) curtain.classList.remove('is-cover'); });
}

/* ── 13 ─ Header davranışı ───────────────────────────────────────────────── */

function initHeader() {
  const header = document.getElementById('k-header');
  if (!header) return;
  let hidden = false;

  ticker.add(() => {
    const solid = scroll.y > 24;
    header.classList.toggle('is-solid', solid);
    if (header.dataset.menuOpen === '1') { header.classList.remove('is-up'); hidden = false; return; }
    const shouldHide = scroll.direction === 1 && scroll.y > scroll.vh * 0.6;
    if (shouldHide !== hidden) { hidden = shouldHide; header.classList.toggle('is-up', hidden); }
  });
}

/* ── Başlat ──────────────────────────────────────────────────────────────── */

function boot() {
  scroll.measure();
  scroll.sample();

  initReveal();
  initScrollLinked();
  initCounters();
  initMarquee();
  initDotField();
  initMagnetic();
  initCursor();
  initHoverFollower();
  initPageTransition();
  initHeader();

  window.addEventListener('resize', queueMeasure);
  window.addEventListener('orientationchange', queueMeasure);
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(queueMeasure);
  // Görseller yüklendikçe yükseklikler değişir — yeniden ölç.
  window.addEventListener('load', queueMeasure);
  if ('ResizeObserver' in window) {
    const ro = new ResizeObserver(queueMeasure);
    ro.observe(document.body);
  }

  document.documentElement.classList.add('k-ready');
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
  boot();
}

export { ticker, scroll, track, progressOf, clamp, lerp, easeOut, easeInOut, env };
