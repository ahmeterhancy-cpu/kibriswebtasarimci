<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'web-tasarim', 'name' => 'Web Tasarım', 'name_en' => 'Web Design', 'type' => 'blog', 'sort_order' => 1],
            ['slug' => 'e-ticaret', 'name' => 'E-Ticaret', 'name_en' => 'E-Commerce', 'type' => 'blog', 'sort_order' => 2],
            ['slug' => 'seo', 'name' => 'SEO', 'name_en' => 'SEO', 'type' => 'blog', 'sort_order' => 3],
            ['slug' => 'kurumsal-site', 'name' => 'Kurumsal', 'name_en' => 'Corporate', 'type' => 'work', 'sort_order' => 1],
            ['slug' => 'magaza', 'name' => 'Mağaza', 'name_en' => 'Store', 'type' => 'work', 'sort_order' => 2],
            ['slug' => 'web-uygulama', 'name' => 'Web Uygulama', 'name_en' => 'Web App', 'type' => 'work', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }

        $posts = [
            [
                'slug' => 'kibrista-web-sitesi-fiyatlari-2026',
                'category' => 'web-tasarim',
                'title' => 'Kıbrıs\'ta web sitesi fiyatları: neye ne kadar ödüyorsunuz?',
                'title_en' => 'Website prices in Cyprus: what are you actually paying for?',
                'excerpt' => 'Aynı işe 3.000 ₺ de deniyor 60.000 ₺ de. Aradaki fark nereden çıkıyor, hangisi size lazım?',
                'excerpt_en' => 'The same job gets quoted at 3,000 ₺ and at 60,000 ₺. Where does the gap come from, and which do you need?',
                'reading_minutes' => 6,
                'body' => '<p>Kıbrıs\'ta web sitesi fiyatları çok geniş bir aralıkta geziyor. Aynı brief\'e 3.000 ₺ de teklif geliyor, 60.000 ₺ de. İkisi de "web sitesi" diyor ama aldığınız şey aynı değil.</p><h2>Ucuz teklifte ne yok?</h2><p>Çok düşük fiyatlı tekliflerin çoğu hazır şablon üzerine kuruluyor. Şablon kötü demek değil; sorun şablonun size değil, size benzeyen on bin işletmeye göre tasarlanmış olması. Pratikte şunlar eksik kalıyor:</p><ul><li><strong>İçerik stratejisi:</strong> hangi sayfada ne yazacağınıza kimse karar vermiyor</li><li><strong>Hız:</strong> şablonların taşıdığı kullanılmayan kod sayfayı ağırlaştırıyor</li><li><strong>SEO altyapısı:</strong> başlık etiketleri, yapısal veri, sitemap çoğu zaman varsayılan halinde</li><li><strong>Destek:</strong> teslimden sonra soru sorabileceğiniz kimse yok</li></ul><h2>Pahalı teklifte ne var?</h2><p>Fiyatı yukarı çeken şey genelde tasarım değil, <strong>iş yükü</strong>: analiz, özel tasarım, yönetim paneli, entegrasyonlar ve test. Bir e-ticaret sitesinde ödeme, kargo, stok ve bildirim akışlarının doğru çalıştığını doğrulamak tek başına günler alıyor.</p><h2>Peki size hangisi lazım?</h2><p>Basit bir kural: <em>siteden ne bekliyorsunuz?</em></p><ul><li>Sadece "internette varım" demek istiyorsanız tek sayfalık bir site yeterli.</li><li>Müşteri sizi araştırıp karar veriyorsa çok sayfalı kurumsal site gerekir.</li><li>Siteden doğrudan satış yapacaksanız e-ticaret ve panel şart.</li></ul><h2>Gizli maliyetleri sorun</h2><p>Teklif alırken şunları net sorun: hosting ve domain dahil mi, kaç yıl? İkinci yıl ne ödeyeceğim? İçeriği kim giriyor? Değişiklik istediğimde ücret var mı? Site benim adıma mı kayıtlı?</p><blockquote>En pahalı site, bir yıl sonra baştan yaptırmak zorunda kaldığınız sitedir.</blockquote><p>Fiyat aralıklarımızı ve her pakete tam olarak neyin dahil olduğunu paketler sayfasında açıkça yazıyoruz.</p>',
                'body_en' => '<p>Website prices in Cyprus span a huge range. The same brief gets quoted at 3,000 ₺ and at 60,000 ₺. Both say "website", but what you receive is not the same thing.</p><h2>What is missing from the cheap quote?</h2><p>Most very low quotes are built on a ready-made template. Templates are not inherently bad; the problem is that the template was designed for ten thousand businesses like yours, not for you. In practice these tend to be missing:</p><ul><li><strong>Content strategy:</strong> nobody decides what goes on which page</li><li><strong>Speed:</strong> unused template code makes pages heavy</li><li><strong>SEO foundations:</strong> title tags, structured data and sitemaps are left at their defaults</li><li><strong>Support:</strong> there is nobody to ask after handover</li></ul><h2>What is in the expensive quote?</h2><p>What raises the price is usually not the design but the <strong>work</strong>: analysis, custom design, an admin panel, integrations and testing. On an e-commerce site, verifying that payment, shipping, stock and notification flows all behave correctly takes days by itself.</p><h2>So which one do you need?</h2><p>A simple rule: <em>what do you expect from the site?</em></p><ul><li>If you only need to exist online, a single page is enough.</li><li>If customers research you before deciding, you need a multi-page corporate site.</li><li>If you will sell directly, e-commerce and a panel are non-negotiable.</li></ul><h2>Ask about hidden costs</h2><p>When you get a quote, ask plainly: are hosting and domain included, and for how long? What do I pay in year two? Who enters the content? Is there a fee for changes? Is the domain registered in my name?</p><blockquote>The most expensive website is the one you have to rebuild a year later.</blockquote><p>Our price ranges and exactly what each package includes are written openly on the pricing page.</p>',
            ],
            [
                'slug' => 'kurumsal-site-yaparken-yapilan-7-hata',
                'category' => 'web-tasarim',
                'title' => 'Kurumsal site yaparken yapılan 7 hata',
                'title_en' => 'Seven mistakes companies make on their website',
                'excerpt' => 'Güzel görünen ama iş getirmeyen sitelerin ortak noktaları. Çoğu tasarım değil, karar hatası.',
                'excerpt_en' => 'What good-looking sites that bring no business have in common — mostly decision errors, not design ones.',
                'reading_minutes' => 5,
                'body' => '<p>Bir sitenin iş getirmemesinin sebebi genelde çirkin olması değil. Aşağıdakiler, yıllardır en sık karşılaştığımız yedi hata.</p><h2>1. Ana sayfada ne sattığınız yazmıyor</h2><p>Ziyaretçi ilk ekranda "burası ne yapıyor" sorusunun cevabını göremiyorsa geri gidiyor. "Hoş geldiniz" bir cevap değil.</p><h2>2. Telefon numarası aramak zorunda kalmak</h2><p>İletişim bilgisi yalnızca iletişim sayfasında duruyor. Oysa satın alma niyeti her sayfada oluşabilir.</p><h2>3. Mobili sonra düşünmek</h2><p>Kıbrıs\'ta trafiğin büyük çoğunluğu telefondan geliyor. Masaüstünde mükemmel, telefonda dağılan bir site pratikte bozuk bir sitedir.</p><h2>4. Stok fotoğraf</h2><p>Gülümseyen yabancı ofis çalışanları güven vermiyor. Kendi ekibinizin ve işinizin gerçek fotoğrafı, düşük kaliteli bile olsa daha ikna edici.</p><h2>5. Referans yok</h2><p>İnsanlar başkalarının ne dediğine bakar. Müşteri yorumu, logo veya sonuç göstermeyen bir site kendi kendini övüyor demektir.</p><h2>6. Sitenin sahibi belirsiz</h2><p>Domain ajans adına kayıtlıysa, siteyi taşımak istediğinizde sorun yaşarsınız. Domain ve hosting <strong>her zaman</strong> sizin adınıza olmalı.</p><h2>7. Yayına alıp unutmak</h2><p>Site canlı bir şey. İçerik eklenmeyen, güncellenmeyen bir site altı ay içinde arama sonuçlarında geriliyor.</p><blockquote>Site bir broşür değil, satış ekibinizin 7/24 çalışan üyesidir.</blockquote>',
                'body_en' => '<p>A site usually fails to bring business for reasons other than being ugly. Here are the seven mistakes we see most often.</p><h2>1. The homepage does not say what you sell</h2><p>If a visitor cannot answer "what does this company do" on the first screen, they leave. "Welcome" is not an answer.</p><h2>2. Making people hunt for the phone number</h2><p>Contact details live only on the contact page — but buying intent can appear on any page.</p><h2>3. Treating mobile as an afterthought</h2><p>Most traffic in Cyprus arrives on a phone. A site that is perfect on desktop and falls apart on mobile is, in practice, a broken site.</p><h2>4. Stock photography</h2><p>Smiling stock-photo office workers build no trust. A real photo of your team and your work is more convincing even at lower quality.</p><h2>5. No social proof</h2><p>People look at what others say. A site with no testimonials, logos or results is just praising itself.</p><h2>6. Unclear ownership</h2><p>If the domain is registered to the agency, moving your site later becomes a problem. Domain and hosting should <strong>always</strong> be in your name.</p><h2>7. Launch and forget</h2><p>A site is a living thing. Without new or updated content it slides down the search results within six months.</p><blockquote>A website is not a brochure. It is the member of your sales team that works around the clock.</blockquote>',
            ],
            [
                'slug' => 'kibrista-yerel-seo-rehberi',
                'category' => 'seo',
                'title' => 'Kıbrıs\'ta yerel SEO: Google\'da ilk sayfaya çıkmak',
                'title_en' => 'Local SEO in Cyprus: getting onto page one',
                'excerpt' => 'Rekabetin düşük olduğu bir pazardasınız. Doğru kurgulanmış bir siteyle ilk sayfa gerçekçi bir hedef.',
                'excerpt_en' => 'You are in a low-competition market. With the right setup, page one is a realistic goal.',
                'reading_minutes' => 7,
                'body' => '<p>Kıbrıs pazarının en büyük avantajı çoğu sektörde rekabetin düşük olması. Türkiye\'de yıllar süren bir SEO çalışması burada aylarla ölçülüyor.</p><h2>Önce teknik temel</h2><p>İçerik üretmeden önce sitenin taranabilir ve hızlı olması gerekiyor:</p><ul><li>Her sayfanın benzersiz bir <strong>title</strong> ve <strong>description</strong> etiketi olmalı</li><li>Mobil sürüm gerçekten kullanılabilir olmalı — Google mobil sürümü indeksliyor</li><li>Sayfa hızı: görseller doğru boyutta ve modern formatta (WebP)</li><li>Yapısal veri (schema.org): işletme bilgileri, hizmetler, SSS</li><li>XML sitemap ve robots.txt doğru yapılandırılmalı</li></ul><h2>Google Business Profile</h2><p>Yerel aramalarda haritada çıkmak, organik sıralamadan bile değerli olabiliyor. Ücretsiz ve çoğu işletme eksik dolduruyor:</p><ul><li>Kategori doğru seçilmeli</li><li>Çalışma saatleri güncel olmalı</li><li>Gerçek fotoğraf yüklenmeli</li><li>Yorumlara cevap verilmeli</li></ul><h2>İçerik: hangi soruları cevaplıyorsunuz?</h2><p>İnsanlar Google\'a marka adı değil, sorun yazıyor. "Girne\'de klima servisi", "Lefkoşa düğün salonu fiyat", "KKTC şirket kurma". Bu soruların her biri bir sayfa konusu.</p><h3>Pratik bir başlangıç planı</h3><ol><li>Müşterilerin size en sık sorduğu 10 soruyu yazın</li><li>Her biri için 600-1000 kelimelik bir sayfa hazırlayın</li><li>Sayfaları birbirine bağlayın</li><li>Ayda 2 içerikle devam edin</li></ol><h2>İki dilli site avantajı</h2><p>Kıbrıs\'ta hem Türkçe hem İngilizce arama yapılıyor. İki dilli bir site, doğru <code>hreflang</code> etiketleriyle kurulduğunda iki ayrı pazarda birden görünür.</p><blockquote>SEO bir kerelik iş değil; en büyük getiriyi düzenli devam edenler alıyor.</blockquote>',
                'body_en' => '<p>The biggest advantage of the Cyprus market is that competition is low in most sectors. SEO work that takes years in Türkiye is measured in months here.</p><h2>Technical foundations first</h2><p>Before producing content, the site has to be crawlable and fast:</p><ul><li>Every page needs a unique <strong>title</strong> and <strong>description</strong></li><li>The mobile version must be genuinely usable — Google indexes mobile</li><li>Page speed: images correctly sized and in a modern format (WebP)</li><li>Structured data (schema.org): business details, services, FAQs</li><li>A correct XML sitemap and robots.txt</li></ul><h2>Google Business Profile</h2><p>Appearing on the map in local searches can be worth more than an organic ranking. It is free, and most businesses fill it in half-heartedly:</p><ul><li>Pick the right category</li><li>Keep opening hours current</li><li>Upload real photos</li><li>Reply to reviews</li></ul><h2>Content: which questions do you answer?</h2><p>People type problems into Google, not brand names. "Air conditioning service Kyrenia", "wedding venue Nicosia price", "company formation in North Cyprus". Each of those is a page.</p><h3>A practical starting plan</h3><ol><li>Write down the 10 questions customers ask you most</li><li>Build a 600-1,000 word page for each</li><li>Link the pages to each other</li><li>Keep going with two pieces a month</li></ol><h2>The bilingual advantage</h2><p>People search in both Turkish and English in Cyprus. A bilingual site, set up with correct <code>hreflang</code> tags, appears in two markets at once.</p><blockquote>SEO is not a one-off task. The biggest returns go to whoever keeps at it.</blockquote>',
            ],
        ];

        foreach ($posts as $i => $post) {
            $categoryId = Category::query()->where('slug', $post['category'])->value('id');
            unset($post['category']);

            BlogPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post + [
                    'category_id' => $categoryId,
                    'author' => 'Kıbrıs Web Tasarımcı',
                    'is_published' => true,
                    'published_at' => now()->subDays(($i + 1) * 6),
                ],
            );
        }
    }
}
