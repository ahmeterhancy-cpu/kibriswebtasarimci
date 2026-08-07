# Kıbrıs Web Tasarımcı — kibriswebtasarimci.com

Kuzey Kıbrıs merkezli web tasarım ve yazılım stüdyosunun tanıtım sitesi.
Laravel 13 + Filament 4, Blade + Tailwind v4, **sıfır JavaScript kütüphanesi**.

---

## Hızlı başlangıç

```bash
composer install
npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run start          # php artisan serve :8124 + vite, birlikte
```

Admin: <http://127.0.0.1:8124/admin> — `admin@kibriswebtasarimci.com` / `kwt2026!`
**Canlıya çıkmadan önce bu şifreyi değiştirin.**

### Örnek portfolyo verisi

`DatabaseSeeder` yalnızca gerçek içeriği (hizmetler, paketler, blog, SSS, ayarlar)
kurar. Portfolyo / referans / yorum bölümlerinin tasarımını yerelde görmek için:

```bash
php artisan db:seed --class=DemoContentSeeder
```

Bu kayıtlar **örnektir, gerçek müşteri işi değildir**. Canlıya çıkmadan önce
admin panelinden silin ve yerine gerçek işlerinizi girin.

---

## Animasyon motoru

`resources/js/motion.js` — harici kütüphane kullanılmaz (GSAP, Lenis, AOS yok).
Tek bir `requestAnimationFrame` döngüsü, ölçümler önbellekli, yalnızca
`transform` / `opacity` yazılır. Üretim çıktısı ~14 kB (gzip 4,7 kB).

| Özellik | Kullanım |
|---|---|
| Kelime/karakter maskesiyle başlık açılışı | `data-split` · `data-split="chars"` · `data-split-step="0.05"` |
| Scroll reveal | `.k-reveal` veya `data-reveal="fade\|left\|right\|scale"` + `data-delay="100..600"` |
| Parallax | `data-parallax="0.18"` |
| Süzülen dev arka plan yazısı | `data-drift="500"` (sarmalayıcıda `data-drift-host`) |
| Yapışkan yatay şerit | `data-hscroll` › `data-hscroll-pane` › `data-hscroll-rail` |
| Üst üste yığılan kartlar | `data-stack` › `data-stack-item` |
| Maskesi açılan görsel | `data-unmask` |
| Sayaç | `data-count="12"` · `data-count-decimals` · `data-count-duration` |
| Scroll hızına tepki veren şerit | `data-marquee="0.55"` › `data-marquee-rail` |
| Magnetic öğe | `data-magnetic="0.3"` |
| İmleç durumu | `data-cursor="cta\|drag"` · `data-cursor-label="…"` |
| İmleci takip eden önizleme | `data-follower` + `data-follower-card` + `data-follower-row` |
| Hero görsel izi | `data-trail` + `data-trail-src` |
| Kıbrıs saati | `data-clock` |

`prefers-reduced-motion` açıkken ve dokunmatik cihazlarda özel imleç, sayfa
perdesi ve scroll'a bağlı efektler otomatik devre dışı kalır.

---

## Bilinmesi gerekenler

1. **Koyu zeminde metin** her zaman inline `style="color:#ffffff"` ile yazılır.
   Tailwind `text-white` tema eşlemesinde güvenilmez.
2. **`overflow-x` için `clip` kullanılır, `hidden` değil.** `overflow:hidden`
   html/body'yi kaydırma kabı yapar ve içerideki tüm `position:sticky` öğeleri
   sessizce bozar (yatay iş şeridi ve süreç kartları buna bağlı).
3. **`data-delay` yalnızca CSS'te tanımlı değerlerle çalışır** (100/200/…/600).
   Ara değer (80, 120) hiç gecikme uygulamaz.
4. **Blade `@json(...)` içine parantez geçen dize veya çok satırlı dizi yazmayın.**
   Direktifin argüman ayrıştırıcısı parantez sayarak kapanış arar ve derleme
   hatası verir. Değerleri `@php` bloğunda hazırlayıp değişken geçirin.
   Aynı sebeple JS yorumlarında da `@json(` yazmayın.
5. **Bileşen attribute'u içinde düz `"` kullanmayın.** `:lead="… \"tırnak\" …"`
   attribute'u erken kapatır; hata çok uzakta (`unexpected token endif`) patlar.
   Tipografik `“…”` kullanın.
5. **Yeni Tailwind sınıfı eklediyseniz `npm run build` + `public/build` commit şart.**
   Deploy öncesi `npm run build` çalıştırıp `git status`'un temiz olduğuna bakın.
6. **PowerShell ile UTF-8 dosya düzenlemeyin.** PS 5.1 `Get-Content -Raw`
   BOM'suz UTF-8'i ANSI sanıp Türkçe karakterleri bozar.
7. **Timezone `Asia/Famagusta`.** Europe/Istanbul veya Europe/Nicosia kullanmayın.
8. **İçeriği koda gömmeyin.** Veri yoksa bölüm gizlenir; sahte placeholder
   gösterilmez.
9. **Önbelleğe Eloquent modeli/koleksiyonu yazmayın.** Dosya ve veritabanı
   sürücüleri geri okurken *"incomplete object"* hatası verir; footer şehir
   listesi buna takıldı. `->map(fn ($m) => [...])->all()` ile düz dizi yazın.

---

## SEO ve yerel arama (GEO)

- **Şehir sayfaları** `/web-tasarim/{slug}` (EN: `/web-design/{slug}`) — panelden
  yönetilir (*Şehir Sayfaları*). "girne web tasarım" gibi yerel aramaların giriş
  kapısı. Her şehrin metni GERÇEKTEN farklı olmalı; aynı metnin şehir adı
  değiştirilmiş kopyaları arama motorlarınca *kapı sayfası* sayılır ve cezalanır.
  Panelde metin alanının altındaki uyarı bunu hatırlatır.
- **Yapısal veri**: site genelinde `ProfessionalService` + `WebSite`; şehir
  sayfalarında şehre özel `ProfessionalService` (`areaServed` + `geo`) ve
  `BreadcrumbList`; şehir listesinde `ItemList`. Site geneli `areaServed`
  yayındaki şehirlerden türer — panelden şehir eklendiğinde kendiliğinden büyür.
- **Uydurma `aggregateRating` / `review` YOK.** Gerçek müşteri değerlendirmesi
  olmadan puan yazmak hem yanıltıcı hem de manuel işlem sebebidir. `SeoTest`
  bunu ayrıca doğruluyor.
- **Filtreli listeler** (`?kategori=`) `noindex, follow` alır ve kanoniği
  filtresiz listeye verir; aynı işler farklı adreslerde tekrarlanmasın diye.
- **Sayfalanmış blog** kendi kanoniğini korur, `rel=prev/next` ile bağlanır ve
  başlığa "— sayfa N" eklenir.
- **404** kendi tasarımlı sayfasıdır, adresten dili okur (`/en/...` → İngilizce)
  ve ziyaretçiyi ana bölümlere yönlendirir.
- Değişiklikten sonra `php artisan test --filter=SeoTest` çalıştırın — kanonik,
  hreflang, JSON-LD geçerliliği ve sitemap kapsamı orada kilitli.

---

## cPanel'e taşırken (SSH yok)

Migration'lar phpMyAdmin'den elle çalıştırılır:

- `--` yorumu yerine `/* */` kullanın (kopyalarken satır sonları kayboluyor;
  `/* */` de iç içe geçmez).
- Import dosyasına `information_schema` sorgusu koymayın — `#1044` verip tüm
  import'u iptal eder.
- İndekslenen `VARCHAR` kolonları 191 olmalı. `AppServiceProvider` içinde
  `Schema::defaultStringLength(191)` zaten ayarlı.
- `.env`'de `FILESYSTEM_DISK=public` olmalı; `config/filament.php` içinde
  `default_filesystem_disk` zaten `public` olarak sabitlenmiştir.
- `filesystems.disks.public.url` göreli `/storage`'dır — APP_URL yanlış kalsa
  bile görsel adresleri bozulmaz.

---

## Yapı

```
app/
  Filament/          admin paneli (kaynaklar, ayar sayfaları, widget'lar)
  Http/Controllers/  sayfa denetleyicileri + sitemap
  Http/Middleware/   SetLocale, MaintenanceMode
  Models/            Service, Work, Package, BlogPost, Category, Brand,
                     Testimonial, FaqItem, QuoteRequest, ContactSubmission, Setting
resources/
  css/app.css        Kooba tasarım sistemi + tüm hareket CSS'i
  js/motion.js       animasyon motoru
  js/app.js          menü, akordiyon, sürükleme, form durumu
  views/             sayfalar, layout bileşenleri, partial'lar
lang/tr, lang/en     arayüz metinleri
```

Diller: Türkçe kökte (`/hizmetler`), İngilizce `/en` önekinde (`/en/services`).
Her sayfa `hreflang` ile eşine bağlanır; `sitemap.xml` iki dili birlikte üretir.
