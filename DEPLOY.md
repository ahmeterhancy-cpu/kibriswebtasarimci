# Canlıya alma — cPanel + Git (SSH'sız kurulum)

Sırayla takip edin. Atlanan adım genelde beyaz ekranla ya da 500 hatasıyla
geri döner.

> **Not:** SSH erişiminiz yok, o yüzden kurulum File Manager + cPanel Git
> üzerinden yapılıyor. cPanel'in Git dağıtımı sunucuda komut çalıştırabildiği
> için `composer`, `migrate` ve önbellek komutları `.cpanel.yml` içinden
> otomatik işleyecek — sizin terminale girmeniz gerekmiyor.

---

## 0. Önce hazır olması gerekenler

| | Neden |
|---|---|
| Alan adı DNS'i sunucuya yönlendirilmiş | Yoksa site açılmaz, SSL alınamaz |
| MySQL veritabanı + kullanıcı (cPanel → MySQL Databases) | Kullanıcıya **ALL PRIVILEGES** verin |
| PHP **8.3+** (cPanel → MultiPHP Manager) | `composer.json` `^8.3` istiyor; 8.2'de kurulmaz |
| Eklentiler: `mbstring`, `intl`, `pdo_mysql`, `openssl`, `fileinfo`, `zip`, `gd` | Laravel + Filament asgarisi |

> **mbstring** bazı paylaşımlı hostlarda kapalı gelir. Kapalıysa Türkçe
> karakterler bozulur ve panel çalışmaz. MultiPHP INI Editor'dan açın.

---

## 1. GitHub deposu

Depo **private** olmalı.

```bash
gh repo create kibriswebtasarimci --private --source=. --remote=origin --push
```

Private depoyu cPanel'in çekebilmesi için bir **Personal Access Token** gerekir:
GitHub → Settings → Developer settings → Personal access tokens → Fine-grained
→ yalnız bu depoya `Contents: Read` yetkisi.

---

## 2. cPanel → Git Version Control

1. **Create** → *Clone a Repository* işaretli
2. **Clone URL**:
   `https://TOKEN@github.com/KULLANICI/kibriswebtasarimci.git`
3. **Repository Path**: `/home/KULLANICI/repositories/kibriswebtasarimci`
4. **Create**

---

## 3. `.cpanel.yml` içindeki kullanıcı adını düzeltin

Depodaki `.cpanel.yml` dosyasında iki satır var:

```yaml
- export DEPLOYPATH=/home/KULLANICI/public_html
- export REPOPATH=/home/KULLANICI/repositories/kibriswebtasarimci
```

`KULLANICI` yerine cPanel kullanıcı adınızı yazıp commit'leyin ve push'layın.
(cPanel'de sağ üstte ya da File Manager'daki `/home/...` yolunda görünür.)

---

## 4. `.env` dosyasını oluşturun

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
DB_DATABASE=KULLANICI_kwt
DB_USERNAME=KULLANICI_kwt
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

## 5. `vendor/` klasörünü yükleyin (yalnız bir kez)

Sunucuda composer yoksa gereklidir; varsa `.cpanel.yml` zaten hallediyor.

1. Proje kökündeki **`vendor-production.zip`** (16 MB) dosyasını File Manager
   ile `public_html` içine yükleyin
2. Sağ tık → **Extract**
3. Zip dosyasını silin

Bu paket `--no-dev` ile hazırlandı; test ve geliştirme araçları içinde yok.

---

## 6. İlk dağıtım

cPanel → Git Version Control → **Manage** → **Deploy HEAD Commit**

`.cpanel.yml` şunları yapar: dosyaları `public_html`e kopyalar, composer'ı
dener, **migration'ları çalıştırır**, önbellekleri üretir, `storage:link`
bağını kurar.

Deploy günlüğünü aynı ekrandan okuyabilirsiniz.

---

## 7. İçeriği yükleyin

Migration'lar tabloları oluşturur ama **içerik boş gelir**. Hizmetler,
paketler, sektörler, şehirler, ofisler ve ayarlar için tohum verisini bir kez
çalıştırmak gerekir.

`.cpanel.yml` içine geçici olarak şu satırı ekleyip bir kez deploy edin,
sonra satırı silin:

```yaml
    - cd $DEPLOYPATH && php artisan db:seed --force
```

> Satırı kalıcı bırakmayın: her dağıtımda panelden yaptığınız düzenlemeleri
> tohum verisine geri döndürür.

---

## 8. Dosya izinleri

File Manager → sağ tık → **Change Permissions**:

| Klasör | İzin |
|---|---|
| `storage` ve tüm alt klasörleri | **775** (Recurse into subdirectories) |
| `bootstrap/cache` | **775** |

Yazılamayan `storage/` = beyaz ekran. Hata log dosyasında da görünmez, çünkü
log dosyası da yazılamaz.

---

## 9. SSL ve HTTPS

1. cPanel → SSL/TLS Status → **Run AutoSSL**
2. Sertifika geldikten **sonra** kökteki `.htaccess` dosyasında HTTPS
   bloğunun başındaki `#` işaretlerini kaldırın

Sertifika yokken açarsanız site erişilemez hâle gelir.

---

## 10. Yayın sonrası kontrol listesi

- [ ] `https://alanadi.com` açılıyor, kilit simgesi yeşil
- [ ] `/admin` girişi çalışıyor → **şifreyi hemen değiştirin.**
      `admin@kibriswebtasarimci.com` / `kwt2026!` yalnızca kurulum şifresidir
      ve bu belgede yazılı olduğu için artık gizli değildir
- [ ] `/sitemap.xml`, `/robots.txt`, `/llms.txt` gerçek alan adını gösteriyor
      (hepsi `localhost` yazıyorsa `APP_URL` yanlış ya da config cache eski)
- [ ] Panelden bir görsel yükleyin, sitede göründüğünü doğrulayın
      (`storage:link` çalışmamışsa görünmez)
- [ ] İletişim formundan test mesajı gönderin, panele düştüğünü görün
- [ ] `.env` dosyasına tarayıcıdan erişilemiyor: `alanadi.com/.env` → 404
- [ ] Google Search Console doğrulaması + sitemap gönderimi
- [ ] Google Business Profile adresi ile sitedeki adres birebir aynı

---

## 11. Sonraki dağıtımlar

```bash
git push
```

cPanel → Git Version Control → **Manage** → **Update from Remote** →
**Deploy HEAD Commit**.

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
