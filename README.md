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
10. **`public/robots.txt` OLMAMALI.** Laravel varsayılanında gelir ve web
    sunucusu statik dosyayı rotadan önce sunar; dinamik `robots.txt` sessizce
    hiç çalışmaz. `SeoTest` dosyanın yokluğunu ayrıca kontrol ediyor.
11. **`User::canAccessPanel()` silinmemeli.** `FilamentUser` arayüzü
    uygulanmazsa Filament yalnız `APP_ENV=local` iken giriş verir; canlıda
    herkes 403 alır ve panel yönetilemez hâle gelir.
12. **`storage/framework/views` Tailwind kaynağı kaldırıldı.** Derlenmiş Blade
    önbelleği ziyaret edilen sayfalara göre değiştiği için çıktı CSS'i her
    derlemede farklı boyutta çıkıyordu (73 kB ↔ 89 kB).

---

## Panelden yönetilenler (Sistem grubu)

İçerik tarafında: **Sektör Sayfaları** ve **Şehir Sayfaları** (İçerik grubu).

| Sayfa | Ne yapar |
|---|---|
| Site Ayarları / Genel | bakım modu ve metinleri |
| Site Markası | site adı, açıklama, **logo (açık + koyu zemin)**, favicon, OG görseli |
| İletişim & Sosyal Medya | e-posta, telefon, WhatsApp, adres, **harita**, 8 sosyal hesap |
| **Ofisler** | Girne / Edirne / Londra — adres, telefon, e-posta, koordinat |
| **Yapay Zekâ Görünürlüğü** | GEO: alıntılanabilir tanım, llms.txt, AI tarayıcı izinleri |
| **Analitik & Tracking** | GA4, GTM, Ads, Meta, LinkedIn, TikTok, Clarity, Hotjar, Yandex, özel script, çerez onayı |
| **Kullanıcılar** | panel kullanıcıları (şifre boş bırakılırsa değişmez) |
| Footer İçeriği | footer tanıtım metni |

Ölçüm script'lerinin hiçbiri `<head>`'e sabit yazılmaz: hepsi
`layouts/partials/analytics.blade.php` içinde durur ve **çerez onayı verilene
kadar tek bir istek bile gitmez**. Panele yalnızca kimlik girilir, kod değil —
istisna, bilerek açık bırakılan özel script alanlarıdır.

---

## SEO ve yerel arama (GEO)

### İçerik mimarisi: sektör × şehir

İki eksen var ve **ağırlık sektörlerde**:

- **Sektör sayfaları** `/sektorler/{slug}` (EN `/industries/{slug}`) — içeriğin
  gerçekten ayrıştığı yer. Otel rezervasyon alır, emlakçı filtreli ilan yayınlar,
  fabrika bayiye şifreli fiyat verir: bu farklar uydurma değil, o yüzden metinler
  birbirinden bağımsız yazılabiliyor. Arama trafiğinin ve yapay zekâ alıntılarının
  hedefi burası. `SeoTest` iki sektörün gövde/giriş metninin aynı olmadığını
  ayrıca kontrol ediyor — kopyala-yapıştır tespit edilirse test kırmızı yanar.
- **Şehir sayfaları** `/web-tasarim/{slug}` (EN `/web-design/{slug}`) — **bilerek
  genel**. Görevi ziyaretçiyi doğru sektör sayfasına taşımak ve hizmet bölgesini
  netleştirmek. Şehir ekonomisi hakkında doğrulanmamış iddia YAZILMAZ.
  Seeder `body` alanını boş bırakır; uzun metin görünümde bölgeye göre üretilir
  (KKTC = "buradayız", Türkiye = "tamamen uzaktan"). Panelde `body` açık: o şehre
  dair **gerçek** bir şey yazılacaksa oraya, sayfanın altında görünür.
- **Adres ve `geo` yalnız ofisin olduğu şehirlerde** basılır (`has_office`).
  Ofisin olmadığı bir şehre adres ya da koordinat yazmak arama motoruna yanlış
  konum sinyali verir ve yerel sonuçlarda ters teper; oralarda `areaServed`
  yeterli. Test bunu kilitliyor.
- **Ayrım bölgeye göre DEĞİL.** İlk kurguda "KKTC = buradayız, Türkiye =
  uzaktan" varsayılmıştı; yanlıştı, Edirne'de gerçek ofis var.

### Müşteri yorumları — uydurma yorum YOK

Sitede yayınlanan her yorumun gerçek bir müşteriden, kendi rızasıyla geldiği
kanıtlanabilir olmalı. Sahte referans TR'de aldatıcı reklam (Reklam Kurulu),
UK'de **DMCC Act 2024** ile doğrudan yasak (Londra ofisi bunun kapsamında) ve
Google tarafında manuel işlem sebebi.

Akış: panelde **Yorum Davetleri** → davet oluştur → tekil bağlantıyı müşteriye
gönder → müşteri `/gorus/{token}` sayfasında kendi yazar ve yayın onayı verir →
yorum `is_active = false` ile bekler → panelden okuyup **Onayla ve yayınla**.

- Bağlantı **tek kullanımlık** ve varsayılan 30 günde kapanır.
- Sayfa `noindex, nofollow` — davet adresleri arama motoruna girmez.
- Rıza kutusu işaretlenmeden kayıt olmaz; onay anı `consented_at` ile saklanır.
- Yapısal veriye (`Review`) **yalnız** `source = form` + rızası olan yorumlar
  yazılır. Panelden elle girilen metin sitede görünür ama arama motoruna
  "değerlendirme" olarak bildirilmez — kanıtı yok.
- Yorum yoksa ana sayfadaki bölüm kendini gizler; boş/placeholder gösterilmez.

### Ofisler — adresin tek kaynağı

`offices` tablosu (panel: *Sistem → Ofisler*). Üç kayıt: Girne (merkez,
Bellapais), Edirne (Özen Plaza), Londra (Covent Garden).

Aynı kayıt **üç yerde birden** okunuyor: iletişim sayfasındaki ofis listesi,
bağlı şehir sayfasının "buradayız" bloğu ve yapısal veri (`Organization.location`
+ şehir sayfasının `PostalAddress`'i). Adres tek yerde tutuluyor çünkü:

- üç ayrı yerde güncellenen bir adres er ya da geç tutarsız kalır,
- ve sitedeki adres ile **Google Business Profile** kaydı arasındaki tutarsızlık
  yerel sıralamayı doğrudan düşürür.

Şehir sayfaları `locations.office_id` ile bağlanır. Bağlıysa "buradayız" +
adres/koordinat; bağlı değilse "tamamen uzaktan" ve yapısal veride **yalnız**
`areaServed`. `SeoTest` aynı adresin iletişim ve şehir sayfasında birebir aynı
göründüğünü ayrıca doğruluyor.
- **Yapısal veri**: site genelinde `ProfessionalService` + `WebSite`; şehir
  sayfalarında şehre özel `ProfessionalService` (`areaServed` + `geo`) ve
  `BreadcrumbList`; şehir listesinde `ItemList`. Site geneli `areaServed`
  yayındaki şehirlerden türer — panelden şehir eklendiğinde kendiliğinden büyür.
- **Uydurma `aggregateRating` YOK** ve `review` yalnız doğrulanmış yorumlar için
  basılır — bkz. aşağıdaki yorum akışı. `SeoTest` ve `TestimonialTest` bunu
  ayrıca doğruluyor.
- **Filtreli listeler** (`?kategori=`) `noindex, follow` alır ve kanoniği
  filtresiz listeye verir; aynı işler farklı adreslerde tekrarlanmasın diye.
- **Sayfalanmış blog** kendi kanoniğini korur, `rel=prev/next` ile bağlanır ve
  başlığa "— sayfa N" eklenir.
- **404** kendi tasarımlı sayfasıdır, adresten dili okur (`/en/...` → İngilizce)
  ve ziyaretçiyi ana bölümlere yönlendirir.
- Değişiklikten sonra `php artisan test --filter=SeoTest` çalıştırın — kanonik,
  hreflang, JSON-LD geçerliliği ve sitemap kapsamı orada kilitli.

### GEO — yapay zekâ aramalarında görünürlük

Klasik SEO "Google'da kaçıncı sıradayız" sorusuydu; GEO ise "ChatGPT'ye
*Kıbrıs'ta web tasarım yapan kim var* diye sorulduğunda cevapta geçiyor muyuz".

- **`/llms.txt`** — modeller için düz metin site özeti. Alıntılanabilir tanım +
  genişletilmiş özet panelden yazılır; hizmetler, paketler (fiyatlarıyla),
  şehirler ve son 20 yazı listeden **otomatik** üretilir. `robots.txt` içinde
  `LLM-Content:` satırıyla bildirilir.
- **AI tarayıcı izinleri** — panelden işaretlenir, `robots.txt`'e yazılır.
  Kritik ayrım: 🔎 *arama/alıntı* botları (OAI-SearchBot, Claude-SearchBot,
  PerplexityBot) kapatılırsa o motorun cevabında **hiç görünülmez**;
  📚 *eğitim* botları (GPTBot, ClaudeBot, CCBot, Google-Extended) yalnız model
  eğitimini etkiler. Google-Extended'ı kapatmak normal Google aramasını
  ETKİLEMEZ — Googlebot ayrı bir bottur.
- Hiç ayar yapılmamışsa **hepsi açıktır**; yeni kurulan bir site sessizce
  yapay zekâ sonuçlarından silinmesin diye.
- Modeller net, doğrulanabilir cümleleri alıntılar. Panel metinlerine pazarlama
  sıfatı değil olgu yazın: ne, kime, nerede, hangi fiyata.

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
