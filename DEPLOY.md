# Canlıya alma — cPanel + Git

Ay Parçası kurulumunda yanarak öğrenilen kurallar bu belgeye işlendi.
Sırayla takip edin.

---

## 0. Önce bunları öğrenin

| Soru | Neden önemli |
|---|---|
| **PHP sürümü kaç?** (MultiPHP Manager **ve** PHP Selector ayrı ayrı) | **8.4.1+ gerekiyor.** Bu kurulumda 8.4 seçildi ✓ |
| Alan adının kök dizini değiştirilebiliyor mu? | Değişemiyorsa uygulama `public_html` içine kurulur, `.htaccess` koruması şart |
| SSH/Terminal var mı? | Yoksa artisan komutları yalnız deploy görevlerinden çalışır |
| Composer kurulu mu? | Yoksa `vendor/` elle yüklenir (`vendor-production.zip` hazır) |
| MySQL veritabanı + kullanıcı | Kullanıcıya **ALL PRIVILEGES** verin |
| Eklentiler: `mbstring`, `intl`, `pdo_mysql`, `openssl`, `fileinfo`, `zip`, `gd` | Laravel + Filament asgarisi |

### PHP 8.4.1 zorunlu — dikkat

`composer.json` `^8.3` yazıyor ve Laravel'in kendi kısıtı da o. **Ama** bağımlılık
ağacındaki Symfony 8 bileşenleri `>=8.4.1` istiyor. Yani:

```
Sunucuda PHP 8.3    →  site açılmaz (beyaz ekran / fatal error)
Sunucuda PHP 8.4.1+ →  çalışır   ← bu kurulumda seçilen
```

`composer.json` içinde `config.platform.php` **8.4.1** olarak sabitlendi.
Bu sayede yerel PHP daha yeni olsa bile (`8.5`) bağımlılıklar 8.4.1 hedefiyle
çözülüyor — sunucudan yeni bir PHP ile üretilmiş `vendor/` yüzünden sitenin
açılmaması, en sık yapılan hata, böylece engellendi.

Sunucu sürümü değişirse bu değeri güncelleyip `composer update` çalıştırın.

> **mbstring** bazı paylaşımlı hostlarda kapalı gelir. Kapalıysa Türkçe
> karakterler bozulur ve panel çalışmaz. MultiPHP INI Editor'dan açın.

---

## 1. GitHub deposu — neden public

cPanel klon adresinde parola/token **kabul etmiyor**:

> *The clone URL cannot include a password.*

SSH yoksa deploy key de üretilemiyor. Yani private depo için pratikte yol
kalmıyor → depo **public** yapıldı.

Public yapmadan önce yapılanlar (yeni projede de yapın):

- `.env` geçmişte **hiç** commit'lenmemiş olmalı — git geçmişi de okunur,
  geçmişte geçen bir parola yanmış sayılır
- Seeder'daki sabit parolalar temizlendi → `ADMIN_PASSWORD` env'den okunur,
  yoksa rastgele üretilip kurulum çıktısında bir kez gösterilir

---

## 2. cPanel → Git Version Control

1. **Create** → *Clone a Repository* açık
2. **Clone URL** (token YOK):
   `https://github.com/ahmeterhancy-cpu/kibriswebtasarimci.git`
3. **Repository Path**: `repositories/kibriswebtasarimci`
4. **Create**

> Klonlamak dosyaları `public_html`e koymaz. Taşıyan şey **deploy**.

---

## 3. `.env` dosyasını oluşturun

File Manager → `public_html` → **+ File** → `.env`
(Ayarlar → *Show Hidden Files* açık olmalı, yoksa dosyayı göremezsiniz.)

```dotenv
APP_NAME="Kıbrıs Web Tasarımcı"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://kibriswebtasarimci.com

APP_LOCALE=tr
APP_TIMEZONE=Asia/Famagusta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kibr4830_kwt
DB_USERNAME=kibr4830_kwt
DB_PASSWORD=veritabani_sifresi

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=mail.kibriswebtasarimci.com
MAIL_PORT=587
MAIL_USERNAME=info@kibriswebtasarimci.com
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=info@kibriswebtasarimci.com
MAIL_FROM_NAME="${APP_NAME}"
```

**`APP_KEY`** için yerelde şunu çalıştırıp çıktıyı yapıştırın:

```bash
php artisan key:generate --show
```

> `APP_DEBUG=true` bırakılırsa hata sayfalarında veritabanı şifresi dahil
> her şey görünür. Canlıda **her zaman** `false`.

---

## 4. `vendor/` klasörünü yükleyin (yalnız bir kez)

Sunucuda composer yoksa gereklidir; varsa `.cpanel.yml` zaten hallediyor.

1. Proje kökündeki **`vendor-production.zip`** (16 MB) dosyasını File Manager
   ile `public_html` içine yükleyin
2. Sağ tık → **Extract**
3. Zip dosyasını silin

Bu paket `--no-dev` ile hazırlandı; test ve geliştirme araçları içinde yok.

---

## 5. İlk dağıtım

cPanel → Git Version Control → **Manage** → **Deploy HEAD Commit**

`.cpanel.yml` şunları yapar: dizinleri açar, dosyaları `public_html`e kopyalar,
`storage/framework` iskeletini kurar, izinleri 775 yapar, composer'ı dener,
**migration'ları çalıştırır**, `optimize` ile önbellek üretir, `storage:link`
bağını kurar.

### Günlüğü okuyun

`.cpanel.yml` kendi günlüğünü yazıyor:

```
/home/kibr4830/deploy-son.log
```

File Manager ile açın. cPanel'in kendi günlüğünü aramaktan daha hızlı ve
`php -v` çıktısı da içinde — **hangi PHP sürümüyle çalıştığını buradan
görürsünüz.** 8.4.1'in altındaysa haber verin.

### Deploy sessizce düşerse

`.cpanel.yml` geçersiz YAML olursa cPanel hata **göstermez**; "Last Deployed"
son başarılı commit'te donar. En sık sebep: bir görev metnine **iki nokta +
boşluk** girmesi (`echo "ENV: VAR"`). Dosyayı düzenlerseniz bu kurala dikkat.

---

## 6. Veritabanını kurun

Migration'lar `.cpanel.yml` içinde otomatik çalışıyor; tablolar kurulur ama
**içerik boş gelir**. İçeriği yüklemenin üç yolu var, sırayla deneyin.

### (a) Deploy görevine tek satır — en basit

`.cpanel.yml` sonuna geçici olarak ekleyin, bir kez deploy edin, satırı **silin**:

```yaml
    - cd /home/kibr4830/public_html && php artisan db:seed --force >> /home/kibr4830/deploy-son.log 2>&1
```

Yönetici şifresi rastgele üretilip **günlüğe yazılır** — `deploy-son.log`
dosyasından alın.

> Satırı kalıcı bırakmayın: her dağıtımda panelden yaptığınız düzenlemeleri
> tohum verisine geri döndürür.

### (b) Tarayıcıdan kurulum

Deploy görevleri hiç çalışmıyorsa (`.env` dosyasına ekleyin):

```dotenv
SETUP_TOKEN=buraya-uzun-ve-rastgele-bir-dize-yazin
```

Sonra açın: `https://alanadi.com/kurulum/AYNI-DIZE`

Migration + seed + `storage:link` + önbellek üretimini çalıştırır, ne yaptığını
ekranda satır satır yazar. **Bittiğinde `.env`'den `SETUP_TOKEN` satırını
silin** — anahtar yokken rota hiç kaydedilmez.

> İkinci kez açılırsa içeriğiniz geri dönmez: tablolar kuruluysa seed atlanır.

### (c) mysqldump + phpMyAdmin — en hızlısı

Ay Parçası'nda en hızlı yol buydu. Yerelde geçici bir MySQL kurup şemayı ve
veriyi dökün, phpMyAdmin'den içe aktarın.

Bu projede yerel veritabanı **SQLite** olduğu için önce MySQL'e taşımak
gerekir; (a) ya da (b) çalışıyorsa buna gerek yok.

phpMyAdmin'e SQL yapıştırırken:

- `--` yorum satırı kullanmayın, `/* */` kullanın (satır sonları kayboluyor)
- `information_schema` sorgusu koymayın — `#1044` verip tüm import'u iptal eder

---

## 7. Dosya izinleri

`.cpanel.yml` her dağıtımda `storage` ve `bootstrap/cache` dizinlerini **775**
yapıyor — normalde elle bir şey gerekmez.

Yine de beyaz ekran görürseniz File Manager → sağ tık → **Change Permissions**
ile doğrulayın (Recurse into subdirectories işaretli).

> Yazılamayan `storage/` = beyaz ekran. Hata log dosyasında da görünmez,
> çünkü log dosyası da yazılamaz.

---

## 8. SSL ve HTTPS

1. cPanel → SSL/TLS Status → **Run AutoSSL**
2. Sertifika geldikten **sonra** kökteki `.htaccess` dosyasında HTTPS
   bloğunun başındaki `#` işaretlerini kaldırın

Sertifika yokken açarsanız site erişilemez hâle gelir.

---

## 9. Yayın sonrası kontrol listesi

- [ ] `https://alanadi.com` açılıyor, kilit simgesi yeşil
- [ ] `/admin` girişi çalışıyor. Şifre kurulum çıktısında bir kez gösterildi;
      kaçırdıysanız `.env`'e `ADMIN_PASSWORD=` ekleyip kullanıcıyı silip
      seed'i tekrar çalıştırın
- [ ] `/sitemap.xml`, `/robots.txt`, `/llms.txt` gerçek alan adını gösteriyor
      (hepsi `localhost` yazıyorsa `APP_URL` yanlış ya da config cache eski)
- [ ] Panelden bir görsel yükleyin, sitede göründüğünü doğrulayın
      (`storage:link` çalışmamışsa görünmez)
- [ ] İletişim formundan test mesajı gönderin, panele düştüğünü görün
- [ ] `.env` dosyasına tarayıcıdan erişilemiyor: `alanadi.com/.env` → 403/404
- [ ] `alanadi.com/vendor/` ve `alanadi.com/storage/logs/` → 403/404
- [ ] `alanadi.com/storage/` altındaki yüklenmiş bir görsel **açılıyor**
      (bu adres kapatılmamalı, panel görselleri buradan servis edilir)
- [ ] Google Search Console doğrulaması + sitemap gönderimi
- [ ] Google Business Profile adresi ile sitedeki adres birebir aynı

---

## 10. Sonraki dağıtımlar

```bash
git push
```

cPanel → Git Version Control → **Manage** →

1. **Update from Remote** — GitHub'daki yeni commit'leri sunucudaki depoya çeker
2. **Deploy HEAD Commit** — çekilen kodu `public_html`e taşır

**İki düğme iki ayrı iş.** Yalnız ikincisine basmak eski kodu tekrar kurar —
bu tuzağa birkaç kez düşüldü. Basdıktan sonra **Last Deployed SHA değişti mi**
kontrol edin; değişmediyse deploy düşmüş demektir (günlüğe bakın).

Migration otomatik çalışır (yalnız yenileri). Seed çalışmaz — panel
içeriğiniz korunur.

---

## Canlıya çıkmadan önce hatırlatma

Şu an sitede duran demo kayıtlar:

- **4 örnek iş** ("Örnek Kurumsal Site", "Örnek E-Ticaret" …) — kapak
  görselleri yok, gri kutu görünüyorlar
- **6 örnek marka logosu**

Gerçek işler eklenene kadar bunları panelden yayından kaldırmak, ziyaretçiye
sitenin yarım kaldığı izlenimini vermemek açısından daha iyi olur.
