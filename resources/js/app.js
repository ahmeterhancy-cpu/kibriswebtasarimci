/* ============================================================================
   Kıbrıs Web Tasarımcı — genel arayüz betiği
   Harici kütüphane yok. Animasyon motoru: ./motion.js
   ========================================================================== */

import './motion';
import { ticker, scroll, clamp, lerp, env } from './motion';

/* ── Mobil / tam ekran menü ──────────────────────────────────────────────── */

function initMenu() {
    const header = document.getElementById('k-header');
    const menu = document.getElementById('k-menu');
    // Biri header'daki hamburger, diğeri menünün kendi kapatma düğmesi.
    const toggles = Array.from(document.querySelectorAll('[data-menu-toggle]'));
    if (!toggles.length || !menu || !header) return;

    const setOpen = (open) => {
        menu.classList.toggle('is-open', open);
        toggles.forEach((t) => {
            t.classList.toggle('is-open', open);
            t.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        // Header koyu perdenin altında kalır; menü kendi üst çubuğunu taşır.
        header.dataset.menuOpen = open ? '1' : '0';
        document.body.style.overflow = open ? 'hidden' : '';
    };

    toggles.forEach((t) => t.addEventListener('click', () => setOpen(!menu.classList.contains('is-open'))));
    menu.addEventListener('click', (e) => {
        if (e.target.closest('a')) setOpen(false);
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && menu.classList.contains('is-open')) setOpen(false);
    });
}

/* ── Akordiyon (SSS) ─────────────────────────────────────────────────────── */

function initAccordions() {
    document.querySelectorAll('[data-acc]').forEach((group) => {
        const items = Array.from(group.querySelectorAll('.k-acc'));
        items.forEach((item) => {
            const btn = item.querySelector('[data-acc-trigger]');
            if (!btn) return;
            btn.addEventListener('click', () => {
                const open = item.classList.contains('is-open');
                if (group.dataset.acc === 'single') {
                    items.forEach((other) => {
                        other.classList.remove('is-open');
                        const b = other.querySelector('[data-acc-trigger]');
                        if (b) b.setAttribute('aria-expanded', 'false');
                    });
                }
                item.classList.toggle('is-open', !open);
                btn.setAttribute('aria-expanded', !open ? 'true' : 'false');
            });
        });
    });
}

/* ── Sürüklenebilir yatay şerit ──────────────────────────────────────────── */

function initDragScroll() {
    document.querySelectorAll('[data-drag-scroll]').forEach((el) => {
        let id = null, startX = 0, startLeft = 0, moved = 0;

        // TUZAK: Burada eskiden pointerdown anında setPointerCapture çağrılıyordu.
        // İşaretçi yakalaması sonraki `click` olayının hedefini de bu kaba taşıyor;
        // o yüzden şeritteki kartların bağlantısı HİÇ açılmıyordu — tıklama linke
        // değil kaba düşüyor, sayfa geçişi de "link yok" deyip çekiliyordu.
        // Yakalamaya gerek de yok: pointermove/pointerup window üzerinden
        // dinleniyor, imleç kabın dışına çıksa bile sürükleme sürüyor.

        const move = (e) => {
            if (e.pointerId !== id) return;
            const dx = e.clientX - startX;
            moved = Math.abs(dx);
            if (moved <= 8) return;
            el.scrollLeft = startLeft - dx;
        };

        const end = (e) => {
            if (e.pointerId !== id) return;
            id = null;
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', end);
            window.removeEventListener('pointercancel', end);
        };

        el.addEventListener('pointerdown', (e) => {
            if (e.button !== 0 || e.pointerType === 'touch') return;
            // Masaüstünde şerit overflow-visible: kaydırma kabı değil, pinli
            // dönüşümle akıyor. Orada sürüklemeyi dinlemenin faydası yok.
            const ox = getComputedStyle(el).overflowX;
            if (ox !== 'auto' && ox !== 'scroll') return;

            id = e.pointerId;
            moved = 0;
            startX = e.clientX;
            startLeft = el.scrollLeft;
            window.addEventListener('pointermove', move);
            window.addEventListener('pointerup', end);
            window.addEventListener('pointercancel', end);
        });

        // Sürüklerken tarayıcı görseli "taşımaya" kalkmasın.
        el.addEventListener('dragstart', (e) => { if (id !== null) e.preventDefault(); });

        // Sürükleme sonrası istemsiz tıklamayı engelle.
        el.addEventListener('click', (e) => {
            if (moved > 8) { e.preventDefault(); e.stopPropagation(); }
        }, true);
    });
}

/* ── Form gönderim durumu ────────────────────────────────────────────────── */

function initForms() {
    document.querySelectorAll('form[data-busy]').forEach((form) => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            const label = btn.querySelector('[data-busy-label]') || btn;
            label.textContent = btn.dataset.busyText || 'Gönderiliyor…';
        });
    });
}

function boot() {
    initMenu();
    initAccordions();
    initDragScroll();
    initForms();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}

export { ticker, scroll, clamp, lerp, env };
