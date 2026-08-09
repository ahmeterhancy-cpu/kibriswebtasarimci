# Canlıya alma — cPanel + Git

Bu belge sırayla takip edilmek üzere yazıldı. Atlanan adım genelde beyaz ekranla
ya da "500 Server Error" ile geri döner.

---

## 0. Önce şunlar hazır olmalı

| | Neden gerekli |
|---|---|
| Alan adı DNS'i sunucuya yönlendirilmiş | Yoksa site açılmaz, SSL alınamaz |
| MySQL veritabanı + kullanıcı (cPanel → MySQL Databases) | Uygulama SQLite ile değil MySQL ile çalışacak |
| PHP sürümü **8.3 veya üstü** (cPanel → MultiPHP Manager) | `composer.json` `^8.3` istiyor; 8.2'de kurulum başarısız olur |
| PHP eklentileri: `mbstring`, `intl`, `pdo_mysql`, `openssl`, `fileinfo`, `zip`, `gd` | Laravel + Filament asgari gereksinimi |

> **mbstring uyarısı:** bazı paylaşımlı hostlarda kapalı geliyor. Kapalıysa
> Türkçe karakterler bozulur ve Filament çalışmaz. MultiPHP INI Editor'dan açın.

---

## 1. GitHub deposu

```bash
gh repo create kibriswebtasarimci --private --source=. --remote=origin --push
```

Depo **private** olmalı: `.env` içinde olmasa da yapılandırma ve iş mantığı
herkese açık durmamalı.

---

## 2. cPanel → Git Version Control

1. **Create** → *Clone a Repository* işaretli
2. **Clone URL**: `https://github.com/KULLANICI/kibriswebtasarimci.git`
   (private depo için GitHub'da bir *Personal Access Token* üretip
   `https://TOKEN@github.com/...` biçiminde girin)
3. **Repository Path**: `/home/KULLANICI/repositories/kibriswebtasarimci`
4. **Create**

---

## 3. Dizin yapısı — en kritik adım

Laravel'de web kökü `public/` dizinidir; cPanel'de ise `public_html`.
İki yoldan biri seçilir.

### (a) SSH/Terminal varsa — önerilen

Uygulama `public_html` dışında durur, yalnız `public/` yayınlanır:

```bash
# public_html'i sil ve public/ dizinine bağla
rm -rf ~/public_html
ln -s ~/repositories/kibriswebtasarimci/public ~/public_html
```

Bu durumda `.cpanel.yml` içindeki `DEPLOYPATH` değişkenini
`/home/KULLANICI/repositories/kibriswebtasarimci` yapın.

### (b) SSH yoksa

Tüm proje `public_html` içine kopyalanır ve kök dizine bir `.htaccess` konur:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Bu yöntemde `.env`, `storage/` ve `vendor/` tarayıcıdan erişilebilir konumda
olur. `public_html/.htaccess` dosyasına şunu da ekleyin:

```apache
RedirectMatch 404 ^/(\.env|storage|vendor|database|app|config|routes|tests)(/|$)
```

---

## 4. `.env` — sunucuda elle oluşturulur

`.env` depoda **yok** ve olmamalı. cPanel → File Manager ile oluşturun:

```dotenv
APP_NAME="Kıbrıs Web Tasarımcı"
APP_ENV=production
APP_KEY=                      # 5. adımda üretilecek
APP_DEBUG=false               # CANLIDA MUTLAKA false
APP_URL=https://kibriswebtasarimci.com

APP_LOCALE=tr
APP_TIMEZONE=Asia/Famagusta   # Europe/Istanbul DEĞİL

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=KULLANICI_kwt
DB_USERNAME=KULLANICI_kwt
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

FILESYSTEM_DISK=public        # panelden yüklenen görseller için

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=info@kibriswebtasarimci.com
MAIL_FROM_NAME="${APP_NAME}"
```

`APP_DEBUG=true` bırakılırsa hata sayfalarında veritabanı şifresi dahil tüm
yapılandırma görünür. Canlıda **her zaman** `false`.

---

## 5. İlk kurulum komutları

SSH varsa:

```bash
cd ~/repositories/kibriswebtasarimci
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

SSH yoksa:

- **vendor/**: yerelde `composer install --no-dev --optimize-autoloader`
  çalıştırıp `vendor/` klasörünü zip'leyin, File Manager ile yükleyip açın.
- **APP_KEY**: yerelde `php artisan key:generate --show` çıktısını `.env`'e
  yapıştırın.
- **Migration + seed**: yerelde `php artisan schema:dump` ile SQL üretip
  phpMyAdmin'den import edin. Kopyalarken `--` yorum satırı kullanmayın,
  `/* */` kullanın (satır sonları kayboluyor).

---

## 6. Dosya izinleri

```bash
chmod -R 775 storage bootstrap/cache
```

Yazılamayan `storage/` = beyaz ekran. Hata `storage/logs/laravel.log` içinde
görünmez, çünkü log dosyası da yazılamaz.

---

## 7. SSL

cPanel → SSL/TLS Status → **Run AutoSSL**. Sonrasında `public/.htaccess`
dosyasına HTTPS yönlendirmesi ekleyin:

```apache
RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 8. Yayın sonrası kontrol listesi

- [ ] `https://alanadi.com` açılıyor, SSL yeşil
- [ ] `https://alanadi.com/admin` giriş çalışıyor — **şifreyi hemen değiştirin**
      (`admin@kibriswebtasarimci.com` / `kwt2026!` yalnız kurulum şifresidir)
- [ ] `/sitemap.xml`, `/robots.txt`, `/llms.txt` doğru adresleri gösteriyor
      (APP_URL yanlışsa hepsi `localhost` yazar)
- [ ] Panelden bir görsel yükleyip sitede göründüğünü doğrulayın
      (`storage:link` çalışmamışsa görünmez)
- [ ] İletişim formu gerçekten e-posta gönderiyor
- [ ] Google Search Console'a mülkiyet doğrulaması + sitemap gönderimi
- [ ] Google Business Profile adresi ile sitedeki adres birebir aynı

---

## 9. Sonraki dağıtımlar

```bash
git push
```

Sonra cPanel → Git Version Control → **Manage** → **Update from Remote** →
**Deploy HEAD Commit**. `.cpanel.yml` dosyasındaki görevler çalışır.

Şema değişikliği varsa migration'ı **elle** çalıştırın — `.cpanel.yml` bunu
bilerek yapmıyor, çünkü otomatik migration veri kaybettirebilir.
