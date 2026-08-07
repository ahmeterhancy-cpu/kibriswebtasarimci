<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'kurumsal-web-tasarimi',
                'title' => 'Kurumsal Web Tasarımı',
                'title_en' => 'Corporate Web Design',
                'excerpt' => 'İşletmenizi ciddiye alınacak şekilde anlatan, mobilde de hızlı çalışan kurumsal site.',
                'excerpt_en' => 'A corporate site that makes your business look the part and stays fast on mobile.',
                'features' => ['Özel tasarım — şablon yok', 'Mobil öncelikli yapı', 'Yönetim paneli (opsiyonel)', 'Google Analytics kurulumu', 'Temel SEO ayarları', 'SSL + hosting + domain'],
                'features_en' => ['Custom design — no templates', 'Mobile-first build', 'Admin panel (optional)', 'Google Analytics setup', 'Baseline SEO', 'SSL + hosting + domain'],
                'body' => '<p>Kurumsal siteniz çoğu müşterinizin sizinle ilk karşılaşma noktası. O ilk on saniyede ya güven verir ya kaybedersiniz.</p><h2>Nasıl çalışıyoruz</h2><p>Önce ne sattığınızı ve kimden farklı olduğunuzu netleştiriyoruz. Tasarım o cevabın üzerine kuruluyor; ters sırayla değil.</p><h3>Kapsam</h3><ul><li>Marka diline uygun özel arayüz tasarımı</li><li>Kurumsal içerik mimarisi ve sayfa planı</li><li>İletişim formu, WhatsApp entegrasyonu, harita</li><li>Sayfa hızı optimizasyonu</li></ul><h2>Süre</h2><p>Tek sayfalık tanıtım siteleri 4-6 iş gününde, çok sayfalı kurumsal siteler 8-12 iş gününde teslim edilir.</p>',
                'body_en' => '<p>Your corporate site is where most customers meet you first. You either earn trust in those ten seconds or lose it.</p><h2>How we work</h2><p>First we get clear on what you sell and why you are different. The design follows that answer, not the other way round.</p><h3>Scope</h3><ul><li>Custom interface design in your brand language</li><li>Content architecture and page plan</li><li>Contact form, WhatsApp integration, map</li><li>Page speed optimisation</li></ul><h2>Timeline</h2><p>Single-page sites ship in 4-6 working days, multi-page corporate sites in 8-12.</p>',
                'sort_order' => 1,
            ],
            [
                'slug' => 'e-ticaret',
                'title' => 'E-Ticaret Siteleri',
                'title_en' => 'E-Commerce',
                'excerpt' => 'Ürün, sipariş, stok ve ödemeyi tek panelden yönettiğiniz; Kıbrıs ve Türkiye\'ye satış yapan mağaza.',
                'excerpt_en' => 'One panel for products, orders, stock and payments — a store that sells across Cyprus and Türkiye.',
                'features' => ['Ürün ve varyant yönetimi', 'Sipariş ve kargo takibi', 'Online ödeme entegrasyonu', 'Stok uyarıları', 'Kampanya ve kupon', 'SMS + e-posta bildirimleri'],
                'features_en' => ['Product and variant management', 'Order and shipping tracking', 'Online payment integration', 'Stock alerts', 'Campaigns and coupons', 'SMS + email notifications'],
                'body' => '<p>E-ticarette site güzel olsun yetmez; sepeti terk etmeyen, ödemesi tek adımda biten bir akış lazım.</p><h2>Panel her zaman dahil</h2><p>E-ticaret paketinde yönetim paneli standarttır. Ürünü, fiyatı, stoğu ve siparişi kendiniz yönetirsiniz; her değişiklik için bize dönmek zorunda kalmazsınız.</p><h3>Tek paket, tam kapsam</h3><p>Kademeli paket yok: sınırsız ürün, gelişmiş panel, ödeme ve kargo entegrasyonu, SMS ve e-posta bildirimleri, gelişmiş satış raporları tek fiyata dahil. Büyüdüğünüzde üst pakete geçmeniz gerekmez.</p><h2>Sadece vitrin isteyenler</h2><p>Online tahsilat istemiyorsanız e-ticaret almanıza gerek yok: Kurumsal paket + katalog modülü ile ürünlerinizi sergileyip siparişi WhatsApp\'tan alabilirsiniz.</p>',
                'body_en' => '<p>In e-commerce, looking good is not enough. You need a flow that keeps carts alive and finishes checkout in one step.</p><h2>The panel is always included</h2><p>The e-commerce package ships with an admin panel as standard. You manage products, prices, stock and orders yourself instead of emailing us for every change.</p><h3>One package, everything in</h3><p>No tiers: unlimited products, advanced panel, payment and shipping integration, SMS and email notifications and advanced sales reporting are all in one price. You never have to upgrade as you grow.</p><h2>Showcase only</h2><p>If you do not need online payments, you do not need e-commerce: take the Corporate package with the catalogue module and collect orders over WhatsApp.</p>',
                'sort_order' => 2,
            ],
            [
                'slug' => 'web-yazilim-ve-panel',
                'title' => 'Web Yazılım & Panel',
                'title_en' => 'Custom Web Software',
                'excerpt' => 'Rezervasyon, üyelik, randevu, bayi paneli — işinize özel yazılan, hazır paketle çözülmeyen işler.',
                'excerpt_en' => 'Booking, membership, appointments, dealer portals — the work no off-the-shelf package solves.',
                'features' => ['İhtiyaç analizi ve akış tasarımı', 'Rol bazlı yetkilendirme', 'Raporlama ekranları', 'Dış servis entegrasyonları', 'API geliştirme', 'Devir teslim eğitimi'],
                'features_en' => ['Requirement analysis and flow design', 'Role-based permissions', 'Reporting screens', 'Third-party integrations', 'API development', 'Handover training'],
                'body' => '<p>Bazı işler hazır çözümlerle dönmez. Rezervasyon takvimi, bayi paneli, üyelik sistemi, saha ekibi uygulaması — bunlar yazılır.</p><h2>Önce akış, sonra kod</h2><p>Ekranları çizmeden önce süreci konuşuyoruz. Yanlış anlaşılmış bir akışın kodu ne kadar temiz yazılırsa yazılsın işe yaramaz.</p><h3>Sık yaptıklarımız</h3><ul><li>Rezervasyon ve randevu sistemleri</li><li>Bayi / müşteri portalları</li><li>İç operasyon panelleri</li><li>Mevcut sisteme API bağlantısı</li></ul>',
                'body_en' => '<p>Some work does not fit an off-the-shelf tool. Booking calendars, dealer portals, membership systems, field-team apps — those get written.</p><h2>Flow first, code second</h2><p>We talk the process through before drawing screens. However clean the code, a misunderstood flow is useless.</p><h3>What we build often</h3><ul><li>Booking and appointment systems</li><li>Dealer and customer portals</li><li>Internal operations panels</li><li>API connections to your existing system</li></ul>',
                'sort_order' => 3,
            ],
            [
                'slug' => 'mobil-uygulama',
                'title' => 'iOS & Android Uygulama',
                'title_en' => 'iOS & Android Apps',
                'excerpt' => 'Tek kod tabanından iki mağaza. Tasarım, geliştirme, App Store ve Google Play yayını dahil.',
                'excerpt_en' => 'Two stores from one codebase. Design, development and App Store / Google Play release included.',
                'features' => ['iOS + Android tek projeden', 'Push bildirim altyapısı', 'Mevcut siteyle / API ile entegrasyon', 'App Store & Google Play yayını', 'Mağaza görselleri ve metinleri', 'Sürüm güncelleme desteği'],
                'features_en' => ['iOS + Android from one project', 'Push notification setup', 'Integration with your site or API', 'App Store & Google Play release', 'Store assets and copy', 'Version update support'],
                'body' => '<p>Uygulama, sitenizin yapamadığı iki şeyi yapar: telefonun ana ekranında durur ve bildirim gönderir. Sadık müşteriye tekrar ulaşmanın en kısa yolu.</p><h2>Tek kod, iki mağaza</h2><p>React Native ile geliştiriyoruz: iOS ve Android tek kod tabanından çıkıyor. Bu, iki ayrı yerli uygulama yazdırmaya göre hem süreyi hem maliyeti belirgin şekilde düşürüyor.</p><h3>Kapsam</h3><ul><li>Arayüz tasarımı ve akış kurgusu</li><li>Geliştirme ve cihaz üstü test</li><li>Push bildirim altyapısı</li><li>Mevcut web sitenizle veya API\'nizle entegrasyon</li><li>App Store ve Google Play yayın süreci</li><li>Mağaza ekran görüntüleri, açıklama metinleri, gizlilik formları</li></ul><h2>Yayın süreci gerçekçi olsun</h2><p>Mağaza incelemeleri bizim kontrolümüzde değil: Apple tarafında ilk inceleme birkaç gün sürebiliyor, Google Play\'de yeni kişisel hesaplarda kapalı test şartı ve bekleme süresi var. Takvimi bu gerçeklere göre planlıyoruz ve süreci sizinle birlikte yürütüyoruz.</p><h2>Kime uygun değil</h2><p>Sadece "bizim de uygulamamız olsun" diyorsanız açıkça söyleyelim: kullanıcının tekrar tekrar açması için bir sebep yoksa uygulama indirilmez. Önce o sebebi konuşuyoruz; yoksa mobil uyumlu bir site daha doğru yatırımdır.</p>',
                'body_en' => '<p>An app does two things your site cannot: it sits on the home screen and it sends notifications. It is the shortest route back to a loyal customer.</p><h2>One codebase, two stores</h2><p>We build with React Native, so iOS and Android come from a single codebase. That cuts both time and cost significantly against commissioning two separate native apps.</p><h3>Scope</h3><ul><li>Interface design and flow</li><li>Development and on-device testing</li><li>Push notification infrastructure</li><li>Integration with your existing site or API</li><li>App Store and Google Play submission</li><li>Store screenshots, descriptions and privacy forms</li></ul><h2>Realistic release timelines</h2><p>Store reviews are outside our control: Apple\'s first review can take several days, and new personal Google Play accounts require a closed test period before release. We plan the schedule around those realities and run the process with you.</p><h2>When it is not the right call</h2><p>If the goal is simply "we should have an app too", we will say so plainly: without a reason to open it repeatedly, an app does not get downloaded. We discuss that reason first — and if there is not one, a mobile-friendly site is the better investment.</p>',
                'sort_order' => 4,
            ],
            [
                'slug' => 'seo-ve-icerik',
                'title' => 'SEO & İçerik',
                'title_en' => 'SEO & Content',
                'excerpt' => 'Kıbrıs\'ta aranan kelimelerde çıkmak için teknik altyapı, içerik planı ve yerel SEO.',
                'excerpt_en' => 'Technical foundations, a content plan and local SEO so you show up in Cyprus searches.',
                'features' => ['Teknik SEO denetimi', 'Anahtar kelime araştırması', 'Google Business Profile', 'Schema / yapısal veri', 'Sayfa hızı iyileştirme', 'Aylık içerik planı'],
                'features_en' => ['Technical SEO audit', 'Keyword research', 'Google Business Profile', 'Schema markup', 'Page speed work', 'Monthly content plan'],
                'body' => '<p>Site yayına girdi diye kimse sizi bulmuyor. Aranan kelimede ilk sayfada olmak ayrı bir iş.</p><h2>Yerelde kazanmak</h2><p>Kıbrıs pazarında rekabet, Türkiye ve İngiltere\'ye göre çok daha düşük. Doğru kurgulanmış bir site ve düzenli içerikle ilk sayfa gerçekçi bir hedef.</p><h3>Neye bakıyoruz</h3><ul><li>Teknik: hız, indekslenebilirlik, yapısal veri, mobil uyum</li><li>İçerik: hangi sorulara cevap veriyorsunuz</li><li>Yerel: Google Business Profile, harita, yorumlar</li></ul>',
                'body_en' => '<p>Nobody finds you just because the site went live. Ranking on page one is separate work.</p><h2>Winning locally</h2><p>Competition in the Cyprus market is far lighter than in Türkiye or the UK. With a properly built site and regular content, page one is a realistic target.</p><h3>What we look at</h3><ul><li>Technical: speed, indexability, structured data, mobile</li><li>Content: which questions you actually answer</li><li>Local: Google Business Profile, maps, reviews</li></ul>',
                'sort_order' => 5,
            ],
            [
                'slug' => 'ui-ux-tasarim',
                'title' => 'UI / UX Tasarım',
                'title_en' => 'UI / UX Design',
                'excerpt' => 'Kullanıcının ne yapacağını düşünmediği arayüzler. Önce akış, sonra piksel.',
                'excerpt_en' => 'Interfaces where users never wonder what to do next. Flow first, pixels second.',
                'features' => ['Kullanıcı akışı haritası', 'Tel kafes (wireframe)', 'Arayüz tasarımı', 'Tasarım sistemi', 'Etkileşim prototipi', 'Erişilebilirlik denetimi'],
                'features_en' => ['User flow mapping', 'Wireframes', 'Interface design', 'Design system', 'Interactive prototype', 'Accessibility review'],
                'body' => '<p>İyi arayüz fark edilmez. Kullanıcı ne yapacağını düşünmez, sadece yapar.</p><h2>Süreç</h2><p>Akış haritası → tel kafes → arayüz → prototip. Her adımda gösteriyoruz; sürpriz teslim yok.</p><h3>Tasarım sistemi</h3><p>Tek seferlik ekran değil, büyüyebilen bir sistem kuruyoruz: renk, tipografi, bileşenler ve kuralları. Yeni sayfa eklendiğinde site dağılmıyor.</p>',
                'body_en' => '<p>Good interfaces go unnoticed. Users do not think about what to do — they just do it.</p><h2>Process</h2><p>Flow map → wireframe → interface → prototype. We show each step; no surprise handovers.</p><h3>Design system</h3><p>We build a system that scales, not one-off screens: colour, type, components and their rules. Adding a page later does not break the site.</p>',
                'sort_order' => 6,
            ],
            [
                'slug' => 'bakim-ve-destek',
                'title' => 'Bakım & Destek',
                'title_en' => 'Care & Support',
                'excerpt' => 'Güncelleme, yedek, güvenlik ve içerik değişiklikleri. Site yayına girdikten sonra da yanınızdayız.',
                'excerpt_en' => 'Updates, backups, security and content changes. We stay after launch.',
                'features' => ['Düzenli yedekleme', 'Güvenlik güncellemeleri', 'İçerik değişiklikleri', 'Kesinti izleme', 'Aylık performans raporu', 'Öncelikli destek hattı'],
                'features_en' => ['Scheduled backups', 'Security updates', 'Content changes', 'Uptime monitoring', 'Monthly performance report', 'Priority support line'],
                'body' => '<p>Siteler bakımsız kalınca bozulur: eklentiler eskir, formlar sessizce çalışmayı bırakır, hız düşer.</p><h2>Paketler</h2><ul><li><strong>Basic:</strong> yedek, güncelleme, küçük içerik düzenlemeleri</li><li><strong>Standart:</strong> Basic + aylık rapor ve öncelikli destek</li><li><strong>Premium:</strong> Standart + sürekli izleme ve geliştirme saati</li></ul><p>İlk ay bakım her pakette ücretsizdir.</p>',
                'body_en' => '<p>Neglected sites decay: plugins age, forms quietly stop working, speed drops.</p><h2>Plans</h2><ul><li><strong>Basic:</strong> backups, updates, small content edits</li><li><strong>Standard:</strong> Basic plus monthly reporting and priority support</li><li><strong>Premium:</strong> Standard plus continuous monitoring and development hours</li></ul><p>The first month of care is free with every package.</p>',
                'sort_order' => 7,
            ],
        ];

        foreach ($services as $service) {
            Service::query()->updateOrCreate(['slug' => $service['slug']], $service + ['is_active' => true]);
        }
    }
}
