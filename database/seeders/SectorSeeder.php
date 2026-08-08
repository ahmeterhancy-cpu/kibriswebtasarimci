<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

/**
 * Sektör sayfaları.
 *
 * Buradaki metinler şehir sayfalarının aksine gerçekten birbirinden farklı,
 * çünkü ihtiyaçlar gerçekten farklı: otel rezervasyon alır, emlakçı ilan
 * yayınlar, restoran menü gösterir, sanayi firması bayiye şifreli fiyat verir.
 *
 * Kural: uydurma referans, müşteri sayısı, "sektör lideri" iddiası YOK. Yazılan
 * her şey ya hizmet tanımı ya da o sektörde herkesin bildiği bir gözlem.
 */
class SectorSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->sectors() as $i => $sector) {
            Sector::query()->updateOrCreate(
                ['slug' => $sector['slug']],
                $sector + ['sort_order' => $i + 1, 'is_active' => true],
            );
        }
    }

    private function sectors(): array
    {
        return [
            [
                'slug' => 'otel-ve-konaklama',
                'name' => 'Otel & Konaklama',
                'name_en' => 'Hotels & Hospitality',
                'icon' => '🏨',
                'headline' => 'Otel web sitesi',
                'headline_en' => 'Hotel websites',
                'intro' => 'Otelin sitesi bir katalog değil, rezervasyon kanalı. Misafiri acentaya kaptırmadan doğrudan size getirmesi gerekir.',
                'intro_en' => 'A hotel site is not a brochure, it is a booking channel. It has to bring the guest to you directly instead of losing them to an OTA.',
                'needs' => [
                    'Misafirin yarısı sizi Türkçe aramıyor — çok dilli olmak şart',
                    'Fotoğraf ağırlıklı ama mobilde saniyeler içinde açılan yapı',
                    'Müsaitlik ve rezervasyon talebi tek adımda',
                    'Booking/Airbnb komisyonunu düşüren doğrudan kanal',
                ],
                'needs_en' => [
                    'Half your guests are not searching in Turkish — multilingual is mandatory',
                    'Photo-heavy but opens in seconds on mobile',
                    'Availability and booking request in a single step',
                    'A direct channel that cuts OTA commission',
                ],
                'features' => [
                    'Oda tipleri, kapasite ve donanım listesi',
                    'Tarih seçmeli rezervasyon talep formu',
                    'Galeri: sıkıştırılmış, hızlı yüklenen görseller',
                    'Konum, ulaşım ve çevre bilgisi',
                    'WhatsApp\'a tek dokunuşla bağlanan iletişim',
                    'Google Business Profile ile uyumlu bilgi yapısı',
                ],
                'features_en' => [
                    'Room types, capacity and amenity lists',
                    'Booking request form with date selection',
                    'Gallery with compressed, fast-loading images',
                    'Location, transport and neighbourhood information',
                    'One-tap WhatsApp contact',
                    'Information structure aligned with Google Business Profile',
                ],
                'body' => '<p>Konaklama işinde sitenin görevi net: misafir Booking\'de sizi bulduktan sonra adınızı Google\'a yazıyor. O anda karşısına çıkan sayfa, aracı komisyonu ödemeden rezervasyon alabileceğiniz tek yer.</p><h2>Çok dillilik lüks değil</h2><p>Kıbrıs ve Antalya gibi pazarlarda trafiğin önemli kısmı Türkçe dışında geliyor. Tek dilli bir site, bu trafiğin tamamını görmezden gelmek demek. Her paketimizde ikinci dil eklenebiliyor; Kurumsal pakette TR + EN zaten dahil.</p><h2>Hız, fotoğraftan önce gelir</h2><p>Otel siteleri görsel ağırlıklıdır ve çoğu bu yüzden yavaştır. Görselleri modern formatlarda sıkıştırıp tembel yüklüyoruz: sayfa açılırken sadece ekranda görünen fotoğraflar iniyor. Misafirin telefonu ve otel wifi\'si buna teşekkür ediyor.</p><h3>Rezervasyon akışı</h3><p>Tam otomatik ödeme alan bir motor mu, yoksa tarih ve kişi sayısı toplayıp size düşen bir talep formu mu — bu bir bütçe kararı. Küçük ve butik işletmelerde talep formu genelde yeterli oluyor; oda sayısı arttıkça takvimli sisteme geçmek mantıklı hâle geliyor. İkisini de yapıyoruz, hangisinin gerektiğini görüşmede konuşuyoruz.</p>',
                'body_en' => '<p>In hospitality the job of the site is clear: after finding you on an OTA, the guest types your name into Google. The page they land on is the only place you can take a booking without paying commission.</p><h2>Multilingual is not a luxury</h2><p>In markets like Cyprus and Antalya a large share of traffic arrives in languages other than Turkish. A single-language site ignores all of it. A second language can be added to any package; the Corporate package already includes TR + EN.</p><h2>Speed comes before photography</h2><p>Hotel sites are image-heavy and most of them are slow for exactly that reason. We compress images into modern formats and load them lazily: only what is on screen downloads first. The guest\'s phone and the hotel wifi both benefit.</p><h3>The booking flow</h3><p>A full engine that takes payment, or a request form that collects dates and guest count and lands in your inbox — that is a budget decision. For small and boutique properties a request form is usually enough; as room count grows a calendar-based system starts to make sense. We build both.</p>',
                'seo_title' => 'Otel Web Sitesi Tasarımı | Rezervasyon Alan, Çok Dilli Site',
                'seo_title_en' => 'Hotel Website Design | Multilingual Sites That Take Bookings',
                'seo_description' => 'Otel ve konaklama işletmeleri için çok dilli, hızlı ve rezervasyon talebi toplayan web sitesi. Oda tipleri, galeri, müsaitlik formu. Fiyatlar açık.',
                'seo_description_en' => 'Multilingual, fast websites that collect booking requests for hotels and guesthouses. Room types, gallery, availability form. Prices published openly.',
            ],

            [
                'slug' => 'restoran-ve-kafe',
                'name' => 'Restoran & Kafe',
                'name_en' => 'Restaurants & Cafés',
                'icon' => '🍽️',
                'headline' => 'Restoran web sitesi',
                'headline_en' => 'Restaurant websites',
                'intro' => 'Müşteri menüyü telefonundan bakıyor, rezervasyonu WhatsApp\'tan yapıyor. Site bu iki işi kusursuz yapmalı, gerisi süs.',
                'intro_en' => 'Guests read the menu on their phone and book over WhatsApp. The site has to nail those two jobs; the rest is decoration.',
                'needs' => [
                    'Menü PDF değil, telefonda okunabilir sayfa olmalı',
                    'Fiyat değişince siteyi beklemeden kendiniz güncelleyebilmelisiniz',
                    'Rezervasyon ve paket sipariş için tek dokunuş',
                    'Google Haritalar ve çalışma saatleri her zaman doğru',
                ],
                'needs_en' => [
                    'The menu must be a readable page, not a PDF',
                    'You should update prices yourself, without waiting for anyone',
                    'One tap for reservations and takeaway orders',
                    'Google Maps and opening hours always accurate',
                ],
                'features' => [
                    'Panelden yönetilen menü: kategori, ürün, fiyat, alerjen',
                    'QR menü olarak da kullanılabilen mobil görünüm',
                    'Rezervasyon formu ve WhatsApp sipariş bağlantısı',
                    'Günün menüsü / kampanya duyuru alanı',
                    'Çalışma saatleri ve konum bloğu',
                    'Instagram akışına bağlantı',
                ],
                'features_en' => [
                    'Menu managed from the panel: categories, items, prices, allergens',
                    'Mobile view that doubles as a QR menu',
                    'Reservation form and WhatsApp ordering link',
                    'Daily special / campaign announcement area',
                    'Opening hours and location block',
                    'Link to the Instagram feed',
                ],
                'body' => '<p>Restoran sitelerinde en sık yapılan hata menüyü PDF olarak koymak. Telefonda açılan PDF\'i okumak için yakınlaştırma yapmak gerekiyor, arama motorları içindeki ürün adlarını göremiyor ve fiyat değiştiğinde yeni dosya hazırlamak zorunda kalıyorsunuz.</p><h2>Menü bir sayfa olmalı</h2><p>Menüyü panelden yönetilen bir yapı olarak kuruyoruz: kategori ekliyor, ürün yazıyor, fiyatı değiştiriyorsunuz. Değişiklik anında yayında. Aynı sayfa telefonda QR menü olarak da çalışıyor, masaya ayrı bir sistem kurmanız gerekmiyor.</p><h2>Sipariş nereden geliyor</h2><p>Kıbrıs\'ta ve Türkiye\'nin çoğu şehrinde paket sipariş hâlâ ağırlıklı olarak telefon ve WhatsApp üzerinden. Bu yüzden siteyi sepetli bir sistemle karmaşıklaştırmadan önce, WhatsApp\'a ürün adıyla birlikte giden bir bağlantı çoğu işletme için daha iyi çalışıyor. Sipariş hacmi büyüdüğünde sepet ve online ödemeye geçmek her zaman mümkün.</p><h3>Yerel aramada görünmek</h3><p>"Yakınımdaki restoran" aramalarında çıkmanın yolu siteden çok Google Business Profile\'dan geçiyor. Sitedeki adres, telefon ve çalışma saati bilgisini oradaki kayıtla birebir aynı tutuyoruz — tutarsızlık yerel sıralamayı doğrudan düşürüyor.</p>',
                'body_en' => '<p>The most common mistake on restaurant sites is publishing the menu as a PDF. It requires pinch-zooming on a phone, search engines cannot read the dish names inside it, and every price change means producing a new file.</p><h2>The menu should be a page</h2><p>We build the menu as a panel-managed structure: you add categories, write items, change prices. Changes are live immediately. The same page works as a QR menu on the phone, so you do not need a separate system for the tables.</p><h2>Where orders actually come from</h2><p>In Cyprus and in most Turkish cities takeaway still arrives mainly by phone and WhatsApp. So before complicating the site with a cart, a link that opens WhatsApp pre-filled with the item name works better for most venues. Moving to a cart and online payment is always possible once volume grows.</p><h3>Showing up in local search</h3><p>Appearing in "restaurants near me" depends more on Google Business Profile than on the site. We keep the address, phone and opening hours on the site identical to that listing — inconsistency directly lowers local ranking.</p>',
                'seo_title' => 'Restoran & Kafe Web Sitesi | Panelden Yönetilen Menü',
                'seo_title_en' => 'Restaurant & Café Websites | Menus You Manage Yourself',
                'seo_description' => 'Restoran ve kafeler için mobilde hızlı, panelden güncellenen menülü web sitesi. QR menü, rezervasyon formu, WhatsApp sipariş bağlantısı.',
                'seo_description_en' => 'Fast mobile websites for restaurants and cafés with a self-managed menu. QR menu, reservation form, WhatsApp ordering.',
            ],

            [
                'slug' => 'emlak-ve-insaat',
                'name' => 'Emlak & İnşaat',
                'name_en' => 'Real Estate & Construction',
                'icon' => '🏗️',
                'headline' => 'Emlak ve inşaat web sitesi',
                'headline_en' => 'Real estate and construction websites',
                'intro' => 'Bu işte site bir vitrin değil, form toplayan bir satış kanalı. Ziyaretçinin ilanı bulup iletişime geçmesi arasındaki her adım kayıp.',
                'intro_en' => 'Here the site is a lead-generating sales channel, not a showroom. Every step between finding a listing and making contact is lost business.',
                'needs' => [
                    'İlan/proje listesi filtrelenebilir olmalı: bölge, tip, fiyat, oda',
                    'Yabancı alıcı için çok dilli sürüm ve para birimi',
                    'Her ilanda doğrudan iletişim ve WhatsApp',
                    'Proje ilerleme fotoğrafları ve teslim takvimi',
                ],
                'needs_en' => [
                    'Listings must be filterable: area, type, price, rooms',
                    'A multilingual version and currency options for foreign buyers',
                    'Direct contact and WhatsApp on every listing',
                    'Construction progress photos and delivery schedule',
                ],
                'features' => [
                    'Panelden yönetilen ilan/proje kayıtları',
                    'Filtre ve arama: bölge, oda sayısı, fiyat aralığı',
                    'İlan detayında galeri, kat planı, konum haritası',
                    'İlan başına talep formu — hangi ilandan geldiği kaydedilir',
                    'Satıldı / rezerve durumu',
                    'Çok dilli içerik ve döviz gösterimi',
                ],
                'features_en' => [
                    'Panel-managed listings and projects',
                    'Filter and search: area, rooms, price range',
                    'Gallery, floor plan and map on the listing detail',
                    'Per-listing enquiry form that records which listing it came from',
                    'Sold / reserved status',
                    'Multilingual content and currency display',
                ],
                'body' => '<p>Emlak ve inşaat, web sitesinin doğrudan paraya döndüğü nadir sektörlerden biri. Ziyaretçi zaten satın almaya niyetli geliyor; sitenin işi onu ilanı bulur bulmaz size ulaştırmak.</p><h2>Filtre olmadan olmaz</h2><p>Yirmi ilanı alt alta sıralamak yeterli değil. Ziyaretçi bölgeye, oda sayısına ve fiyat aralığına göre daraltamıyorsa listeyi terk ediyor. İlan sayısı arttıkça bu daha da belirginleşiyor.</p><h2>Talebin nereden geldiğini bilmek</h2><p>Genel bir "iletişim" formu, hangi ilanla ilgilenildiğini söylemez. Her ilana kendi formunu koyuyoruz; size gelen mesajda ilan adı ve bağlantısı hazır oluyor. Bu tek başına dönüş süresini ciddi biçimde kısaltıyor.</p><h3>Yabancı alıcı</h3><p>Kıbrıs\'ta ve Antalya\'da alıcının önemli kısmı yurt dışından. İngilizce şart; pazara göre Rusça veya Almanca da anlamlı oluyor. Fiyatları ikinci bir para biriminde göstermek, alıcının hesap yapmak için siteden çıkmasını engelliyor.</p><h3>İnşaat firmaları için fark</h3><p>Emlakçıda ilan, inşaatçıda proje var. Proje sayfalarında ilerleme fotoğrafları ve teslim tarihi, alıcıya "bu iş yürüyor" demenin en doğrudan yolu. Aynı yapı, tamamlanmış projeler için referans arşivi olarak da çalışıyor.</p>',
                'body_en' => '<p>Real estate and construction is one of the few sectors where a website turns into money directly. The visitor already intends to buy; the site\'s job is to connect them to you the moment they find the right listing.</p><h2>Filters are not optional</h2><p>Stacking twenty listings in a column is not enough. If a visitor cannot narrow by area, room count and price range, they leave. The more listings you have, the sharper this gets.</p><h2>Knowing where the enquiry came from</h2><p>A generic contact form does not tell you which property the person was looking at. We put a form on every listing; the message that reaches you already contains the listing name and link. That alone shortens response time considerably.</p><h3>Foreign buyers</h3><p>In Cyprus and Antalya a large share of buyers come from abroad. English is essential; depending on the market Russian or German can be worth adding. Showing prices in a second currency stops buyers leaving the site to do the maths.</p><h3>What differs for construction firms</h3><p>Agencies have listings, developers have projects. Progress photos and a delivery date on a project page are the most direct way of telling a buyer that the work is moving. The same structure later works as an archive of completed projects.</p>',
                'seo_title' => 'Emlak ve İnşaat Web Sitesi | Filtreli İlan ve Proje Sayfaları',
                'seo_title_en' => 'Real Estate & Construction Websites | Filterable Listings',
                'seo_description' => 'Emlak ofisleri ve inşaat firmaları için filtrelenebilir ilan/proje sistemi, ilan başına talep formu, çok dilli yapı ve döviz gösterimi.',
                'seo_description_en' => 'Filterable listing and project systems for agencies and developers, per-listing enquiry forms, multilingual structure and currency display.',
            ],

            [
                'slug' => 'e-ticaret-ve-perakende',
                'name' => 'E-Ticaret & Perakende',
                'name_en' => 'E-Commerce & Retail',
                'icon' => '🛒',
                'headline' => 'E-ticaret sitesi',
                'headline_en' => 'E-commerce stores',
                'intro' => 'Mağaza kurmak kolay, satan mağaza kurmak ayrı iş. Ürün, stok, kargo ve ödemenin tek panelden yönetilmesi gerekir.',
                'intro_en' => 'Setting up a store is easy; setting up a store that sells is another matter. Products, stock, shipping and payments have to live in one panel.',
                'needs' => [
                    'Sepetten ödemeye giden yolun kısa olması',
                    'Stok ve varyant (beden, renk) yönetimi',
                    'Kıbrıs\'a ve Türkiye\'ye ayrı kargo/teslimat kuralları',
                    'Mobilde tek elle tamamlanabilen kasa adımı',
                ],
                'needs_en' => [
                    'A short path from cart to payment',
                    'Stock and variant (size, colour) management',
                    'Separate shipping rules for Cyprus and Türkiye',
                    'A checkout that can be finished one-handed on mobile',
                ],
                'features' => [
                    'Sınırsız ürün, kategori ve varyant',
                    'Stok takibi ve tükenen ürün davranışı',
                    'Kupon, indirim ve kampanya kuralları',
                    'Sipariş yönetimi, durum bildirimleri, fatura bilgileri',
                    'Kargo bölgeleri ve ücret kuralları',
                    'Online ödeme entegrasyonu veya kapıda ödeme',
                ],
                'features_en' => [
                    'Unlimited products, categories and variants',
                    'Stock tracking and out-of-stock behaviour',
                    'Coupons, discounts and campaign rules',
                    'Order management, status notifications, invoice details',
                    'Shipping zones and pricing rules',
                    'Online payment integration or cash on delivery',
                ],
                'body' => '<p>E-ticarette kaybın çoğu kasada oluyor. Ziyaretçi ürünü beğeniyor, sepete atıyor ve ödeme adımında vazgeçiyor. Sebep genelde tasarım değil: zorunlu üyelik, uzun form, belirsiz kargo ücreti ya da mobilde yanlış çalışan bir alan.</p><h2>Kasa adımı kısa olmalı</h2><p>Üyeliksiz alışverişe izin veriyoruz, adres formunu asgari alana indiriyoruz ve kargo ücretini sepette — kasada değil — gösteriyoruz. Sürpriz kargo bedeli, sepeti terk etmenin en bilinen sebebi.</p><h2>Kıbrıs ve Türkiye aynı kurallarla çalışmıyor</h2><p>Teslimat bölgeleri, kargo süreleri ve ödeme yöntemleri iki pazarda farklı. Sistemi bölge bazlı kuruyoruz: hangi bölgeye hangi ücret, hangi ödeme yöntemi açık, bunu panelden siz belirliyorsunuz.</p><h3>Ödeme</h3><p>Online ödeme için sanal POS başvurusu ve banka onayı gerekiyor; bu süreç bizden bağımsız ilerliyor. Altyapıyı hazır kuruyoruz, onay gelene kadar kapıda ödeme ve havale ile satışa başlayabiliyorsunuz.</p><h3>Panelli mi, panelsiz mi</h3><p>Ürünü kendiniz eklemek istiyorsanız panel şart. Ürün sayısı çok az ve nadiren değişiyorsa panelsiz bir kurgu daha ucuz oluyor. Paketler sayfasında iki seçeneğin de fiyatı yazılı.</p>',
                'body_en' => '<p>In e-commerce most of the loss happens at checkout. The visitor likes the product, adds it to the cart, and abandons at payment. The cause is rarely design: forced registration, a long form, unclear shipping cost, or a field that misbehaves on mobile.</p><h2>Checkout must be short</h2><p>We allow guest checkout, reduce the address form to the minimum, and show shipping cost in the cart — not at the payment step. A surprise delivery charge is the best-known reason for abandonment.</p><h2>Cyprus and Türkiye do not run on the same rules</h2><p>Delivery zones, transit times and payment methods differ between the two markets. We build the system zone-based: which zone gets which rate, which payment methods are open — you set it from the panel.</p><h3>Payments</h3><p>Online payment requires a virtual POS application and bank approval, a process that runs independently of us. We build the infrastructure ready; until approval arrives you can start selling with cash on delivery and bank transfer.</p><h3>With or without a panel</h3><p>If you want to add products yourself, a panel is essential. If the catalogue is tiny and rarely changes, a panel-free build is cheaper. Both prices are published on the packages page.</p>',
                'seo_title' => 'E-Ticaret Sitesi Kurulumu | Stok, Kargo ve Ödeme Tek Panelde',
                'seo_title_en' => 'E-Commerce Store Setup | Stock, Shipping and Payments in One Panel',
                'seo_description' => 'Kıbrıs ve Türkiye\'ye satış yapan e-ticaret sitesi: sınırsız ürün, varyant, stok, kupon, kargo bölgeleri ve online ödeme. Fiyat açıkça yazılı.',
                'seo_description_en' => 'E-commerce stores selling into Cyprus and Türkiye: unlimited products, variants, stock, coupons, shipping zones and online payment.',
            ],

            [
                'slug' => 'saglik-ve-klinik',
                'name' => 'Sağlık & Klinik',
                'name_en' => 'Health & Clinics',
                'icon' => '🩺',
                'headline' => 'Klinik ve sağlık web sitesi',
                'headline_en' => 'Clinic and healthcare websites',
                'intro' => 'Hasta önce güven arıyor, sonra randevu. Site bu sırayı bozarsa çalışmıyor.',
                'intro_en' => 'Patients look for trust first and an appointment second. A site that reverses that order does not work.',
                'needs' => [
                    'Hekim ve uzmanlık bilgisi öne çıkmalı',
                    'Randevu talebi telefonu aramadan bırakılabilmeli',
                    'Sağlık turizmi hedefleniyorsa çok dilli yapı',
                    'Hasta verisi toplanıyorsa KVKK uyumu',
                ],
                'needs_en' => [
                    'Clinician credentials must be prominent',
                    'Appointment requests without having to call',
                    'A multilingual structure if you target health tourism',
                    'Data-protection compliance when collecting patient details',
                ],
                'features' => [
                    'Hekim profilleri: uzmanlık, eğitim, deneyim',
                    'Tedavi ve hizmet detay sayfaları',
                    'Randevu talep formu ve WhatsApp bağlantısı',
                    'Sık sorulanlar — hasta kaygılarını karşılayan bölüm',
                    'Klinik galerisi ve konum bilgisi',
                    'KVKK aydınlatma metni ve onay kutusu',
                ],
                'features_en' => [
                    'Clinician profiles: specialty, education, experience',
                    'Treatment and service detail pages',
                    'Appointment request form and WhatsApp link',
                    'An FAQ section that answers patient concerns',
                    'Clinic gallery and location',
                    'Privacy notice and consent checkbox',
                ],
                'body' => '<p>Sağlıkta karar duygusal ve temkinli veriliyor. Ziyaretçi fiyat karşılaştırmadan önce "bu kişi işini biliyor mu" sorusunu cevaplamak istiyor. Bu yüzden hekim profilleri, uzmanlık alanları ve tedavi anlatımları sitenin en önemli parçası.</p><h2>İçerik tıbbi iddia değil, bilgi olmalı</h2><p>Tedavi sayfalarını "işlem nedir, kimlere uygulanır, süreç nasıl işler, iyileşme ne kadar sürer" başlıklarıyla kuruyoruz. Sonuç garantisi veren ya da öncesi/sonrası vaadi taşıyan ifadelerden kaçınıyoruz — hem mevzuat açısından riskli, hem güven kırıcı.</p><h2>Randevu</h2><p>Çoğu klinikte tam otomatik takvim gerekmiyor; hastanın adı, telefonu, tercih ettiği gün ve şikâyetini bırakabildiği bir form yeterli oluyor. Talep size ulaştığında sekreter arıyor. Hasta hacmi büyükse takvimli sisteme geçilebiliyor.</p><h3>Sağlık turizmi</h3><p>Kıbrıs\'ta ve Antalya\'da yurt dışından hasta hedefleniyorsa İngilizce sürüm asgari şart. Bu durumda ulaşım, konaklama ve süreç takvimi gibi bilgiler de sayfaya giriyor — hasta yalnızca tedaviyi değil, seyahati planlıyor.</p><h3>KVKK</h3><p>Form üzerinden sağlık bilgisi toplanıyorsa aydınlatma metni ve açık rıza kutusu zorunlu. Bunu formun içine kuruyoruz; metnin hukuki içeriğini sizin danışmanınızın vermesi gerekiyor.</p>',
                'body_en' => '<p>Health decisions are made cautiously and emotionally. Before comparing prices, a visitor wants to answer one question: does this person know what they are doing? That is why clinician profiles, specialties and treatment explanations are the most important part of the site.</p><h2>Content should inform, not claim</h2><p>We structure treatment pages around what the procedure is, who it suits, how the process runs and how long recovery takes. We avoid outcome guarantees and before/after promises — legally risky and corrosive to trust.</p><h2>Appointments</h2><p>Most clinics do not need a full calendar system; a form capturing name, phone, preferred day and complaint is enough, with the front desk calling back. Calendar booking becomes worthwhile at higher volumes.</p><h3>Health tourism</h3><p>If you target patients from abroad, an English version is the minimum. Travel, accommodation and process timelines then belong on the page too — the patient is planning a trip, not just a treatment.</p><h3>Data protection</h3><p>If health information is collected through a form, a privacy notice and explicit consent checkbox are mandatory. We build them into the form; the legal wording has to come from your own adviser.</p>',
                'seo_title' => 'Klinik ve Sağlık Web Sitesi | Randevu Talebi Alan Yapı',
                'seo_title_en' => 'Clinic & Healthcare Websites | Built to Take Appointment Requests',
                'seo_description' => 'Klinik, muayenehane ve sağlık kuruluşları için hekim profilleri, tedavi sayfaları, randevu formu ve KVKK uyumlu veri toplama.',
                'seo_description_en' => 'Clinician profiles, treatment pages, appointment forms and compliant data collection for clinics and healthcare providers.',
            ],

            [
                'slug' => 'hukuk-muhasebe-danismanlik',
                'name' => 'Hukuk, Muhasebe & Danışmanlık',
                'name_en' => 'Law, Accounting & Consulting',
                'icon' => '⚖️',
                'headline' => 'Danışmanlık ve ofis web sitesi',
                'headline_en' => 'Professional services websites',
                'intro' => 'Bu işlerde site satış yapmaz, referans kontrolünü geçer. Karşı taraf sizinle çalışmadan önce bakar.',
                'intro_en' => 'Here the site does not sell; it passes a reference check. The other side looks before they engage.',
                'needs' => [
                    'Uzmanlık alanları net ve ayrı sayfalarda',
                    'Ekip, deneyim ve kurumsal bilgiler açık',
                    'Ciddiyeti bozmayan, sade ve hızlı tasarım',
                    'İhale ve teklif süreçlerinde referans verilebilir yapı',
                ],
                'needs_en' => [
                    'Practice areas stated clearly on separate pages',
                    'Team, experience and corporate details visible',
                    'Restrained, fast design that does not undercut seriousness',
                    'A structure you can cite in tenders and proposals',
                ],
                'features' => [
                    'Uzmanlık/hizmet alanı sayfaları',
                    'Ekip profilleri ve kurumsal bilgiler',
                    'Bilgi yazıları — mevzuat ve süreç açıklamaları',
                    'İki dilli yapı (TR + EN)',
                    'Randevu/görüşme talep formu',
                    'Kariyer bölümü',
                ],
                'features_en' => [
                    'Practice-area pages',
                    'Team profiles and corporate information',
                    'Insight articles explaining regulation and process',
                    'Bilingual structure (TR + EN)',
                    'Consultation request form',
                    'Careers section',
                ],
                'body' => '<p>Hukuk bürosu, muhasebe ofisi ya da danışmanlık firmasında müşteri genelde tavsiyeyle geliyor. Site yeni müşteri bulmuyor; tavsiye edilen kişinin arkasındaki firmayı doğruluyor. Bu yüzden ölçüt "ne kadar dikkat çekici" değil, "ne kadar güven verici".</p><h2>Uzmanlık alanları ayrı sayfa olmalı</h2><p>Tek bir "hizmetlerimiz" sayfasında maddeler hâlinde sıralamak yeterli değil. Her uzmanlık alanı kendi sayfasında anlatıldığında hem ziyaretçi aradığını buluyor, hem arama motorunda o konuda görünür oluyorsunuz.</p><h2>Bilgi yazıları işe yarıyor</h2><p>Mevzuat değişikliklerini ve süreçleri sade dille anlatan yazılar, bu sektörde en çok arama trafiği getiren içerik türü. Aynı zamanda yapay zekâ motorlarının alıntıladığı içerik de bu — soru soran birine cevap veren metinler.</p><h3>Ölçülü tasarım</h3><p>Animasyon ve efekt bu sektörde ters tepebiliyor. Sade tipografi, net hiyerarşi ve hızlı yüklenen sayfalar tercih ediyoruz.</p><h3>Kariyer</h3><p>Nitelikli personel rekabetinin yoğun olduğu bir alan. Açık pozisyonların ve başvuru formunun sitede olması, ilan sitelerine bağımlılığı azaltıyor.</p>',
                'body_en' => '<p>For a law firm, accountancy practice or consultancy, clients usually arrive by referral. The site does not find new clients; it verifies the firm behind the person who was recommended. So the measure is not how striking it is, but how much confidence it creates.</p><h2>Practice areas deserve their own pages</h2><p>Listing them as bullets on a single services page is not enough. When each area has its own page, visitors find what they came for and you become visible in search for that subject.</p><h2>Insight articles work</h2><p>Plain-language explanations of regulatory changes and processes bring the most search traffic in this sector. They are also the content generative engines quote most — text that answers a question.</p><h3>Restrained design</h3><p>Animation and effects can backfire here. We favour clean typography, clear hierarchy and pages that load fast.</p><h3>Careers</h3><p>Competition for qualified staff is intense. Publishing open roles and an application form on your own site reduces dependence on job boards.</p>',
                'seo_title' => 'Hukuk, Muhasebe ve Danışmanlık Web Sitesi | Kurumsal Yapı',
                'seo_title_en' => 'Law, Accounting & Consulting Websites | Corporate Structure',
                'seo_description' => 'Hukuk büroları, muhasebe ofisleri ve danışmanlık firmaları için uzmanlık alanı sayfaları, ekip profilleri, bilgi yazıları ve iki dilli yapı.',
                'seo_description_en' => 'Practice-area pages, team profiles, insight articles and bilingual structure for law firms, accountants and consultancies.',
            ],

            [
                'slug' => 'egitim-ve-kurs',
                'name' => 'Eğitim & Kurs',
                'name_en' => 'Education & Training',
                'icon' => '🎓',
                'headline' => 'Okul ve kurs web sitesi',
                'headline_en' => 'School and course websites',
                'intro' => 'Kayıt dönemi kısa. Site, veliyi ya da öğrenciyi bilgi aramaktan kayıt formuna en kısa yoldan götürmeli.',
                'intro_en' => 'Enrolment windows are short. The site has to move a parent or student from research to registration by the shortest route.',
                'needs' => [
                    'Program/kurs detayları: içerik, süre, ücret, kontenjan',
                    'Ön kayıt ve bilgi talep formu',
                    'Kayıt dönemi duyuruları ve takvim',
                    'Uluslararası öğrenci hedefleniyorsa İngilizce sürüm',
                ],
                'needs_en' => [
                    'Programme details: content, duration, fee, capacity',
                    'Pre-registration and enquiry forms',
                    'Enrolment announcements and calendar',
                    'An English version if you target international students',
                ],
                'features' => [
                    'Program ve kurs detay sayfaları',
                    'Ön kayıt formu, kontenjan durumu',
                    'Eğitmen profilleri',
                    'Duyuru ve takvim bölümü',
                    'Sık sorulanlar: ücret, ödeme, devam koşulları',
                    'Çok dilli içerik',
                ],
                'features_en' => [
                    'Programme and course detail pages',
                    'Pre-registration form and capacity status',
                    'Instructor profiles',
                    'Announcements and calendar',
                    'FAQ: fees, payment, attendance rules',
                    'Multilingual content',
                ],
                'body' => '<p>Eğitim kurumlarında trafik yıla yayılmıyor, kayıt dönemlerinde patlıyor. Sitenin o kısa pencerede net cevap vermesi gerekiyor: hangi program, ne kadar sürüyor, ne kadar tutuyor, nasıl başvurulur.</p><h2>Ücret bilgisi</h2><p>Ücreti gizlemek başvuruyu artırmıyor, aksine ciddiyetsizlik izlenimi bırakıyor. Net bir rakam veremiyorsanız aralık ya da "görüşmede netleşir" ifadesi bile boş bırakmaktan iyi çalışıyor.</p><h2>Ön kayıt</h2><p>Tam bir öğrenci bilgi sistemi kurmadan, ön kayıt formu çoğu kurum için yeterli: ad, iletişim, ilgilenilen program ve not alanı. Kontenjan takibi gerekiyorsa panelden yönetiliyor.</p><h3>Uluslararası öğrenci</h3><p>Kıbrıs\'ta üniversite çevresindeki kurslar ve dil okulları için İngilizce sürüm doğrudan pazar genişletiyor. Vize, konaklama ve ulaşım bilgileri de bu durumda sayfanın parçası oluyor.</p><h3>Duyuru düzeni</h3><p>Kayıt tarihleri, sınav takvimi ve tatil duyuruları panelden giriliyor; sitenin ana sayfasında güncel duyuru kendiliğinden öne çıkıyor.</p>',
                'body_en' => '<p>Traffic to education sites is not spread over the year; it spikes during enrolment. In that short window the site has to answer clearly: which programme, how long, how much, how to apply.</p><h2>Publishing fees</h2><p>Hiding the fee does not increase applications; it reads as evasive. If you cannot give a firm figure, a range or "confirmed at interview" still works better than leaving it blank.</p><h2>Pre-registration</h2><p>Without building a full student information system, a pre-registration form is enough for most institutions: name, contact, programme of interest and a notes field. Capacity tracking is handled from the panel when needed.</p><h3>International students</h3><p>For language schools and courses around universities in Cyprus, an English version directly widens the market. Visa, accommodation and travel information then belong on the page too.</p><h3>Announcements</h3><p>Enrolment dates, exam calendars and holiday notices are entered from the panel and surface automatically on the home page.</p>',
                'seo_title' => 'Okul ve Kurs Web Sitesi | Ön Kayıt Formu ve Program Sayfaları',
                'seo_title_en' => 'School & Course Websites | Pre-Registration and Programme Pages',
                'seo_description' => 'Okullar, kurslar ve dil merkezleri için program detay sayfaları, ön kayıt formu, kontenjan takibi, duyuru sistemi ve çok dilli yapı.',
                'seo_description_en' => 'Programme pages, pre-registration forms, capacity tracking, announcements and multilingual structure for schools and training centres.',
            ],

            [
                'slug' => 'sanayi-ve-bayi-agi',
                'name' => 'Sanayi & Bayi Ağı',
                'name_en' => 'Industry & Dealer Networks',
                'icon' => '🏭',
                'headline' => 'Sanayi ve B2B web sitesi',
                'headline_en' => 'Industrial and B2B websites',
                'intro' => 'Burada site son tüketiciye değil, satın alma birimine ve bayiye konuşuyor. Ürün kataloğu ve teknik doküman merkezde.',
                'intro_en' => 'Here the site talks to procurement teams and dealers, not end consumers. The product catalogue and technical documents sit at the centre.',
                'needs' => [
                    'Teknik özellikleriyle aranabilir ürün kataloğu',
                    'Katalog, teknik çizim ve sertifika indirme',
                    'Bayiye özel şifreli fiyat/stok alanı',
                    'İhracat hedefliyorsanız İngilizce sürüm',
                ],
                'needs_en' => [
                    'A searchable product catalogue with technical specs',
                    'Downloadable catalogues, drawings and certificates',
                    'A password-protected price/stock area for dealers',
                    'An English version if you export',
                ],
                'features' => [
                    'Ürün kataloğu: kategori, teknik özellik tablosu, kod',
                    'PDF katalog ve doküman arşivi',
                    'Teklif talep formu — ürün seçmeli',
                    'Bayi girişi: şifreli fiyat listesi ve sipariş formu',
                    'Sertifika ve kalite belgeleri bölümü',
                    'İngilizce sürüm ve ihracat bilgileri',
                ],
                'features_en' => [
                    'Product catalogue: categories, spec tables, part codes',
                    'PDF catalogue and document archive',
                    'Quote request form with product selection',
                    'Dealer login: protected price list and order form',
                    'Certificates and quality documents',
                    'English version and export information',
                ],
                'body' => '<p>Sanayi ve B2B tarafında siteye gelen kişi genelde bir satın alma sorumlusu ya da mühendis. Pazarlama diliyle değil, teknik veriyle ikna oluyor: ürün kodu, ölçü, malzeme, kapasite, sertifika.</p><h2>Katalog aranabilir olmalı</h2><p>PDF katalog indirtmek tek başına yetmiyor; ürünler sitede kendi sayfalarında olmadığında arama motorunda hiç görünmüyorsunuz. Ürünleri teknik özellik tablosuyla birlikte sayfa hâline getiriyor, PDF\'i de indirilebilir tutuyoruz.</p><h2>Bayi alanı</h2><p>Bayi fiyatı herkese açık olamaz. Şifreli bir alan kuruyoruz: bayi giriş yapıyor, kendi fiyat listesini ve stok durumunu görüyor, sipariş formunu dolduruyor. Bu, telefonla fiyat teyidi trafiğini belirgin şekilde azaltıyor.</p><h3>İhracat</h3><p>İngilizce sürüm, yurt dışı alıcının ilk durağı. Ürün adları ve teknik terimlerin doğru çevrilmesi burada tasarımdan daha önemli — yanlış terim, aramada hiç bulunmamak demek.</p><h3>Sertifikalar</h3><p>Kalite belgeleri, uygunluk sertifikaları ve test raporları ihale dosyalarında isteniyor. Bunları düzenli bir arşiv olarak yayınlamak, her talepte e-posta trafiği yaşamaktan daha verimli.</p>',
                'body_en' => '<p>On the industrial and B2B side, the person visiting is usually a buyer or an engineer. They are convinced by technical data, not marketing language: part code, dimensions, material, capacity, certification.</p><h2>The catalogue must be searchable</h2><p>Offering a PDF download is not enough; if products do not exist as pages, you are invisible in search. We turn products into pages with specification tables and keep the PDF downloadable alongside.</p><h2>The dealer area</h2><p>Dealer pricing cannot be public. We build a protected area: the dealer logs in, sees their own price list and stock, and submits an order form. This noticeably reduces phone traffic for price confirmations.</p><h3>Export</h3><p>The English version is the first stop for an overseas buyer. Correct translation of product names and technical terms matters more than design here — a wrong term means never being found.</p><h3>Certificates</h3><p>Quality certificates, conformity documents and test reports are requested in tender files. Publishing them as an organised archive beats handling each request by email.</p>',
                'seo_title' => 'Sanayi ve B2B Web Sitesi | Ürün Kataloğu ve Bayi Paneli',
                'seo_title_en' => 'Industrial & B2B Websites | Product Catalogue and Dealer Panel',
                'seo_description' => 'Üretici ve sanayi firmaları için teknik özellikli ürün kataloğu, doküman arşivi, teklif formu ve şifreli bayi fiyat/sipariş paneli.',
                'seo_description_en' => 'Technical product catalogues, document archives, quote forms and password-protected dealer pricing panels for manufacturers.',
            ],
        ];
    }
}
