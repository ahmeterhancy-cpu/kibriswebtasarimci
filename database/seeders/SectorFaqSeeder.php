<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

/**
 * Sektör sayfalarının sık sorulanları.
 *
 * Sorular bilerek HER SEKTÖRDE FARKLI. Aynı dört soruyu on beş sayfaya
 * kopyalamak sayfaları birbirinin kopyası yapar; sektör sayfalarının tüm
 * değeri birbirinden gerçekten ayrışmasından geliyor.
 *
 * Cevaplarda uydurma rakam, müşteri sayısı ya da süre sözü yok. Bilinmeyen
 * yerde "sağlayıcıya bağlı" ya da "mevzuata bağlı" demek, yanlış söz
 * vermekten iyidir — yanlış söz teklif aşamasında sorun olarak geri döner.
 */
class SectorFaqSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->data() as $slug => $sets) {
            Sector::where('slug', $slug)->update([
                'faq' => $sets['tr'],
                'faq_en' => $sets['en'],
            ]);
        }
    }

    /** @return array<string, array{tr: array<int, array<string, string>>, en: array<int, array<string, string>>}> */
    private function data(): array
    {
        return [

            'otel-ve-konaklama' => [
                'tr' => [
                    ['q' => 'Siteye rezervasyon motoru kuruyor musunuz?', 'a' => 'Sıfırdan ödeme alan bir rezervasyon motoru yazmıyoruz; kullandığınız kanal yöneticisine ya da rezervasyon sağlayıcınıza bağlıyoruz. Henüz bir sağlayıcınız yoksa tarih seçmeli talep formuyla başlamak çoğu tesis için yeterli oluyor, hacim büyüdüğünde motora geçilebiliyor.'],
                    ['q' => 'Oda fiyatlarını sitede göstermeli miyiz?', 'a' => 'Sezona göre değişen bir fiyatı sabit rakam olarak yazmak sonradan sorun çıkarıyor. Sezon tablosu ya da "başlangıç fiyatı" biçimini öneriyoruz; ikisini de panelden kendiniz güncelliyorsunuz.'],
                    ['q' => 'Rezervasyonlar aracı sitelerden geliyor, kendi sitemize ne gerek var?', 'a' => 'Aracı siteler üzerinden gelen her rezervasyonda komisyon ödüyorsunuz. Misafirin önemli kısmı rezervasyondan önce tesisin kendi sitesine de bakıyor; orada doğrudan iletişim bulduğunda komisyonsuz rezervasyon mümkün oluyor.'],
                    ['q' => 'Kaç dilde yapmalıyız?', 'a' => 'Misafir profilinize bakıyoruz. Ağırlık yurt dışındansa İngilizce şart; pazarınıza göre Rusça ya da Almanca anlamlı olabiliyor. Kullanılmayan bir dil yönetim yükü, çeviri maliyeti ve bakımsız kalma riski getiriyor.'],
                ],
                'en' => [
                    ['q' => 'Do you build a booking engine?', 'a' => 'We do not write a payment-taking booking engine from scratch; we connect the site to your channel manager or booking provider. If you do not have one yet, a date-based enquiry form is enough for most properties, and you can move to an engine as volume grows.'],
                    ['q' => 'Should we publish room rates?', 'a' => 'Publishing a fixed figure for a rate that changes by season causes problems later. We recommend a seasonal table or a "from" price instead; you update both yourself from the panel.'],
                    ['q' => 'Bookings already come from the OTAs. Why do we need our own site?', 'a' => 'Every OTA booking costs you commission. A large share of guests also look at the property\'s own site before booking, and when they find direct contact there, a commission-free booking becomes possible.'],
                    ['q' => 'How many languages do we need?', 'a' => 'It depends on your guest mix. If most arrive from abroad, English is essential; Russian or German can make sense depending on your market. A language nobody uses brings admin load, translation cost and the risk of going stale.'],
                ],
            ],

            'restoran-ve-kafe' => [
                'tr' => [
                    ['q' => 'Menüyü PDF olarak koysak olmaz mı?', 'a' => 'Telefonda PDF okumak için yakınlaştırma gerekiyor, arama motorları içindeki ürün adlarını göremiyor ve her fiyat değişikliğinde yeni dosya hazırlamanız gerekiyor. Menüyü panelden yönetilen bir sayfa olarak kuruyoruz.'],
                    ['q' => 'QR menü için ayrı bir sistem mi almalıyız?', 'a' => 'Hayır. Sitedeki menü sayfası telefonda QR menü olarak da çalışıyor. Masalara koyacağınız karekod doğrudan o sayfaya gidiyor, ikinci bir abonelik gerekmiyor.'],
                    ['q' => 'Siteden sipariş alabilir miyiz?', 'a' => 'Alabilirsiniz. Ama sipariş hacminiz henüz küçükse, ürün adıyla birlikte WhatsApp\'a giden bir bağlantı sepetli sistemden daha iyi çalışıyor ve komisyon doğurmuyor. Hacim büyüdüğünde sepete ve online ödemeye geçmek mümkün.'],
                    ['q' => 'Fiyat değişince sizi mi aramamız gerekiyor?', 'a' => 'Hayır. Fiyat, ürün ve kategori panelden sizin kontrolünüzde; değişiklik anında yayına giriyor.'],
                ],
                'en' => [
                    ['q' => 'Can we just publish the menu as a PDF?', 'a' => 'A PDF needs pinch-zooming on a phone, search engines cannot read the dish names inside it, and every price change means producing a new file. We build the menu as a page you manage from the panel.'],
                    ['q' => 'Do we need a separate QR menu service?', 'a' => 'No. The menu page on your site doubles as the QR menu. The code on your tables points straight to it, with no second subscription.'],
                    ['q' => 'Can we take orders through the site?', 'a' => 'You can. But while your order volume is still small, a link that opens WhatsApp pre-filled with the item name works better than a cart and costs no commission. Moving to a cart and online payment is possible once volume grows.'],
                    ['q' => 'Do we have to call you when prices change?', 'a' => 'No. Prices, items and categories are yours to manage from the panel, and changes go live immediately.'],
                ],
            ],

            'emlak-ve-insaat' => [
                'tr' => [
                    ['q' => 'Yüzlerce ilanı tek tek elle mi gireceğiz?', 'a' => 'Mevcut ilanlarınız bir portalda ya da tabloda duruyorsa toplu aktarım yapıyoruz. Aktarım sonrası günlük giriş panelden; fotoğraf, kat planı ve konum tek ekranda.'],
                    ['q' => 'Satılan ilanı siteden silmeli miyiz?', 'a' => 'Silmemenizi öneriyoruz. İlanı "satıldı" olarak işaretlemek hem adresi canlı tutuyor hem de yeni ziyaretçiye iş yaptığınızı gösteriyor. Silinen her sayfa arama motorunda biriktirdiği değeri de götürüyor.'],
                    ['q' => 'Fiyatları hangi para biriminde gösterelim?', 'a' => 'Alıcı kitlenize bağlı. Yurt dışı alıcı ağırlıktaysa ikinci bir para biriminde gösterim, alıcının hesap yapmak için siteden çıkmasını engelliyor. Dönüşüm kurunu panelden siz belirliyorsunuz.'],
                    ['q' => 'Hangi ilandan talep geldiğini görebilir miyiz?', 'a' => 'Evet. Her ilanın kendi formu var; size ulaşan mesajda ilan adı ve bağlantısı hazır geliyor. Genel bir iletişim formunda bu bilgi kayboluyor.'],
                ],
                'en' => [
                    ['q' => 'Do we have to enter hundreds of listings by hand?', 'a' => 'If your listings already live in a portal or a spreadsheet, we migrate them in bulk. Day-to-day entry then happens in the panel, with photos, floor plan and location on one screen.'],
                    ['q' => 'Should we delete a listing once it sells?', 'a' => 'We recommend against it. Marking it "sold" keeps the address live and shows new visitors that you close deals. Every deleted page also takes the search value it had accumulated with it.'],
                    ['q' => 'Which currency should we show prices in?', 'a' => 'It depends on your buyers. If a large share come from abroad, showing a second currency stops them leaving the site to do the maths. You set the conversion rate from the panel.'],
                    ['q' => 'Can we see which listing an enquiry came from?', 'a' => 'Yes. Every listing has its own form, and the message that reaches you already contains the listing name and link. A generic contact form loses that information.'],
                ],
            ],

            'e-ticaret-ve-perakende' => [
                'tr' => [
                    ['q' => 'KKTC\'den kartla tahsilat yapabilir miyiz?', 'a' => 'Yapabilirsiniz. Yerel bankaların sanal POS hizmeti ya da bölgeye hizmet veren ödeme sağlayıcıları kullanılıyor; Türkiye\'ye de satıyorsanız oradaki sağlayıcılar ayrıca kurulabiliyor. Başvuru ve onay süreci sağlayıcıya ait, biz entegrasyonu yapıyoruz.'],
                    ['q' => 'Mevcut ürünlerimizi aktarabilir misiniz?', 'a' => 'Ürünler bir tabloda, başka bir sitede ya da pazaryeri panelinde duruyorsa aktarıyoruz. Varyant, stok ve görsel yapısını önce birlikte netleştiriyoruz; aktarımdan sonra düzeltmek daha pahalı oluyor.'],
                    ['q' => 'Pazaryerlerinde satıyoruz, kendi sitemize gerek var mı?', 'a' => 'Pazaryeri her satışta komisyon alıyor ve müşteri bilgisini sizinle paylaşmıyor. Kendi siteniz komisyonsuz satış ve kendi müşteri listeniz demek. İkisi birbirinin alternatifi değil, genelde birlikte yürütülüyor.'],
                    ['q' => 'Kargo takibi sitede görünebilir mi?', 'a' => 'Kargo firmanızın takip numarası sipariş kaydına girildiğinde müşteri kendi hesabından görebiliyor. Firma entegrasyon sağlıyorsa bu otomatikleşiyor; sağlamıyorsa manuel giriş kalıyor.'],
                ],
                'en' => [
                    ['q' => 'Can we take card payments from Northern Cyprus?', 'a' => 'Yes. Local banks\' virtual POS services or payment providers serving the region are used; if you also sell into Türkiye, providers there can be added separately. The application and approval belong to the provider — we build the integration.'],
                    ['q' => 'Can you migrate our existing products?', 'a' => 'If your products live in a spreadsheet, another site or a marketplace panel, we migrate them. We agree the variant, stock and image structure with you first — fixing it after the migration costs more.'],
                    ['q' => 'We sell on marketplaces. Do we need our own store?', 'a' => 'Marketplaces take a commission on every sale and do not hand you the customer data. Your own store means commission-free sales and your own customer list. The two are not alternatives; most sellers run both.'],
                    ['q' => 'Can customers track shipments on the site?', 'a' => 'Once the carrier\'s tracking number is entered on the order, the customer sees it in their account. Where the carrier offers an integration this becomes automatic; where it does not, entry stays manual.'],
                ],
            ],

            'saglik-ve-klinik' => [
                'tr' => [
                    ['q' => 'Hangi içeriği yayınlayabileceğimiz konusunda sınır var mı?', 'a' => 'Sağlık alanında tanıtım içeriği mevzuatla sınırlı ve kurallar hekimin bağlı olduğu meslek örgütüne göre değişiyor. Biz hukuki görüş vermiyoruz; sayfa metinlerini siz ya da danışmanınız onaylamadan yayına almıyoruz.'],
                    ['q' => 'Siteden randevu alınabilir mi?', 'a' => 'Randevu talebi formu kuruyoruz: hasta tarih aralığı ve iletişim bilgisi bırakıyor, sizin personeliniz onaylıyor. Takvimle çift yönlü senkron çalışan bir sistem gerekiyorsa bu ayrı kapsam olarak konuşuluyor.'],
                    ['q' => 'Formdan gelen hasta bilgileri güvende mi?', 'a' => 'Form verisi şifreli bağlantı üzerinden gidiyor ve yalnızca panele erişimi olan kullanıcılar görüyor. Hangi bilgiyi topladığınıza dikkat etmenizi öneriyoruz; gerekli olmayan sağlık verisini formda hiç istememek en güvenli yol.'],
                    ['q' => 'Hekim profillerini kim güncelleyecek?', 'a' => 'Siz. Hekim, uzmanlık alanı, eğitim geçmişi ve fotoğraf panelden yönetiliyor; kadro değiştiğinde bize haber vermeniz gerekmiyor.'],
                ],
                'en' => [
                    ['q' => 'Are there limits on what we can publish?', 'a' => 'Promotional content in healthcare is restricted by regulation, and the rules vary with the professional body the clinician belongs to. We do not give legal advice: no page text goes live until you or your adviser has approved it.'],
                    ['q' => 'Can patients book through the site?', 'a' => 'We build an appointment request form: the patient leaves a preferred window and contact details, and your staff confirms. If you need two-way sync with a live calendar, that is scoped separately.'],
                    ['q' => 'Is patient information from the form secure?', 'a' => 'Form data travels over an encrypted connection and is visible only to users with panel access. We recommend being deliberate about what you collect — the safest approach is not to ask for health data you do not need.'],
                    ['q' => 'Who keeps the clinician profiles up to date?', 'a' => 'You do. Clinicians, specialities, training history and photos are managed from the panel, so you do not need to contact us when your team changes.'],
                ],
            ],

            'hukuk-muhasebe-danismanlik' => [
                'tr' => [
                    ['q' => 'Mesleğimizde reklam kısıtı var, site açabilir miyiz?', 'a' => 'Bilgilendirme amaçlı internet sitesi genelde mümkün; sınırı bağlı olduğunuz meslek örgütünün kuralları belirliyor. Metinleri bu kurallara göre siz onaylıyorsunuz, biz hukuki görüş vermiyoruz. Tasarım tarafında abartılı iddia ve karşılaştırma dilinden bilerek uzak duruyoruz.'],
                    ['q' => 'Makale yazmaya vaktimiz yok, blog bölümü boş mu kalacak?', 'a' => 'Boş bir blog yoktan kötü. Siteyi makale bölümü olmadan da kurabiliyoruz, sonradan eklemek mümkün. Yazmaya karar verirseniz konu ve iskeleti çıkarıp size onaya getirebiliyoruz.'],
                    ['q' => 'Müvekkil formundan gelen bilgiler nerede duruyor?', 'a' => 'Kendi sitenizin veritabanında ve panele erişimi olan kullanıcılarda. Gizlilik gerektiren dosya alışverişini form üzerinden yapmamanızı, ilk temastan sonra kendi güvenli kanalınıza geçmenizi öneriyoruz.'],
                    ['q' => 'İki dilli yapmak gerekir mi?', 'a' => 'Müvekkil profilinize bağlı. Yabancı yatırımcı, şirket kuruluşu ya da taşınmaz işleri yapıyorsanız İngilizce sürüm doğrudan iş getiriyor. Yalnızca yerel müvekkille çalışıyorsanız tek dil yeterli.'],
                ],
                'en' => [
                    ['q' => 'Our profession restricts advertising. Can we have a website?', 'a' => 'An informational website is generally possible; the limits are set by your professional body. You approve the text against those rules — we do not give legal advice. On the design side we deliberately avoid exaggerated claims and comparative language.'],
                    ['q' => 'We have no time to write articles. Will the blog sit empty?', 'a' => 'An empty blog is worse than none. We can build the site without an articles section and add it later. If you decide to write, we can draft the topics and outlines for your approval.'],
                    ['q' => 'Where do client enquiries end up?', 'a' => 'In your own site\'s database, visible to users with panel access. We recommend not exchanging confidential files through the form and moving to your own secure channel after first contact.'],
                    ['q' => 'Do we need a bilingual site?', 'a' => 'It depends on your clients. If you handle foreign investors, company formation or property work, an English version brings business directly. If you work only with local clients, one language is enough.'],
                ],
            ],

            'egitim-ve-kurs' => [
                'tr' => [
                    ['q' => 'Kayıt dönemi dışında site boş mu duracak?', 'a' => 'Duyuru, sınav takvimi ve program sayfaları yıl boyunca aranıyor. Kayıt dönemi trafiğin patladığı an, ama sayfaların arama sonuçlarında yer tutabilmesi için öncesinde yayında olması gerekiyor.'],
                    ['q' => 'Ücretleri yazmalı mıyız?', 'a' => 'Ücreti gizlemek başvuruyu artırmıyor. Net rakam veremiyorsanız aralık ya da "görüşmede belirlenir" ifadesi bile boş bırakmaktan iyi çalışıyor; veli ya da öğrenci cevabı bulamadığında sormuyor, başka yere bakıyor.'],
                    ['q' => 'Kontenjan takibi yapabilir miyiz?', 'a' => 'Program başına kontenjan ve kalan yer panelden yönetiliyor. Dolduğunda kayıt formu kendiliğinden kapanabiliyor ya da yedek liste moduna geçebiliyor.'],
                    ['q' => 'Online ders satışı da yapabilir miyiz?', 'a' => 'Ders içeriğini barındırmak ve erişim yönetmek ayrı bir kapsam; teklif aşamasında ayrıca konuşuyoruz. Yalnızca ön kayıt ve ücret tahsilatı istiyorsanız bu standart yapıda çözülüyor.'],
                ],
                'en' => [
                    ['q' => 'Will the site sit idle outside enrolment season?', 'a' => 'Announcements, exam calendars and programme pages are searched all year. Enrolment is when traffic spikes, but the pages have to be live beforehand to hold their place in the results.'],
                    ['q' => 'Should we publish fees?', 'a' => 'Hiding the fee does not increase applications. If you cannot give a firm figure, a range or "confirmed at interview" still works better than leaving it blank — a parent or student who cannot find the answer does not ask, they look elsewhere.'],
                    ['q' => 'Can we track capacity?', 'a' => 'Capacity and remaining places are managed per programme from the panel. When a programme fills, the form can close itself or switch to a waiting-list mode.'],
                    ['q' => 'Can we sell online courses too?', 'a' => 'Hosting course content and managing access is a separate scope that we discuss at the quote stage. If you only need pre-registration and fee collection, that fits the standard build.'],
                ],
            ],

            'sanayi-ve-bayi-agi' => [
                'tr' => [
                    ['q' => 'Bayi fiyatını herkese göstermeden nasıl paylaşırız?', 'a' => 'Şifreli bir bayi alanı kuruyoruz. Bayi giriş yapıyor, yalnızca kendi fiyat listesini ve stok durumunu görüyor, sipariş formunu dolduruyor. Genel ziyaretçi bu bölümü hiç görmüyor.'],
                    ['q' => 'PDF katalog yeterli değil mi?', 'a' => 'Yalnızca PDF indirten site, ürünleri arama sonuçlarında görünmez yapıyor. Ürünleri teknik özellik tablosuyla birlikte sayfa hâline getiriyoruz; PDF de indirilebilir kalıyor, ikisi birbirini dışlamıyor.'],
                    ['q' => 'Binlerce ürün kodumuz var, hepsini girmek gerekir mi?', 'a' => 'Gerekmiyor. En çok sorulan ürün ailelerinden başlamak ve kalanı tablo aktarımıyla eklemek daha hızlı sonuç veriyor. Ürün ailesi yapısını önce netleştiriyoruz.'],
                    ['q' => 'İhracat için İngilizce sürüm şart mı?', 'a' => 'Yurt dışı alıcı hedefliyorsanız evet. Burada tasarımdan önemlisi terim doğruluğu: ürün adları ve teknik terimler yanlış çevrildiğinde aramada hiç bulunmuyorsunuz. Terim listesini sizinle birlikte çıkarıyoruz.'],
                ],
                'en' => [
                    ['q' => 'How do we share dealer pricing without making it public?', 'a' => 'We build a password-protected dealer area. The dealer signs in, sees only their own price list and stock, and submits an order form. Ordinary visitors never see that section.'],
                    ['q' => 'Is a PDF catalogue not enough?', 'a' => 'A site that only offers a PDF leaves your products invisible in search. We turn products into pages with specification tables and keep the PDF downloadable alongside — the two are not mutually exclusive.'],
                    ['q' => 'We have thousands of part codes. Do they all need entering?', 'a' => 'No. Starting with the product families you are asked about most and adding the rest by spreadsheet import usually gets results faster. We agree the family structure first.'],
                    ['q' => 'Is an English version essential for export?', 'a' => 'If you target overseas buyers, yes. Terminology matters more than design here: if product names and technical terms are translated wrongly, you are never found in search. We build the term list with you.'],
                ],
            ],

            'rent-a-car-ve-otomotiv' => [
                'tr' => [
                    ['q' => 'Müsaitlik takvimi gerçek zamanlı mı çalışıyor?', 'a' => 'Araç bazında müsait olmayan tarihler panelden işaretleniyor ve sitede kapalı görünüyor. Mevcut bir filo yazılımınız varsa ona bağlanması ayrı kapsam olarak değerlendiriliyor.'],
                    ['q' => 'Kapora ya da tam ödeme alabilir miyiz?', 'a' => 'Ödeme sağlayıcınız kurulduğunda ikisi de mümkün. Pratikte çoğu firma önce yalnızca rezervasyon talebi alıp onayı kendisi veriyor, ödeme adımını hacim oturduktan sonra ekliyor.'],
                    ['q' => 'Sezonluk fiyat farkını nasıl yönetiyoruz?', 'a' => 'Araç başına sezon tablosu tanımlanıyor; günlük, haftalık ve aylık fiyat ayrı girilebiliyor. Değişiklik panelden anında yayına giriyor.'],
                    ['q' => 'İkinci el satış ilanlarımızı da aynı sitede yayınlayabilir miyiz?', 'a' => 'Yayınlayabilirsiniz. Kiralık filo ve satılık ilanlar ayrı listeler olarak kuruluyor; satılık tarafta marka, model, yıl ve kilometre filtresi ekleniyor.'],
                ],
                'en' => [
                    ['q' => 'Is the availability calendar real time?', 'a' => 'Unavailable dates are marked per vehicle in the panel and show as blocked on the site. Connecting it to an existing fleet system is assessed as a separate scope.'],
                    ['q' => 'Can we take a deposit or full payment?', 'a' => 'Both are possible once your payment provider is set up. In practice most firms start by taking a booking request and confirming it themselves, then add the payment step once volume settles.'],
                    ['q' => 'How do we handle seasonal pricing?', 'a' => 'A seasonal table is defined per vehicle, with separate daily, weekly and monthly rates. Changes go live from the panel immediately.'],
                    ['q' => 'Can we also list used cars for sale on the same site?', 'a' => 'Yes. The rental fleet and the sales listings are built as separate lists, with make, model, year and mileage filters on the sales side.'],
                ],
            ],

            'turizm-ve-tur-acentesi' => [
                'tr' => [
                    ['q' => 'Tur kontenjanını site takip edebilir mi?', 'a' => 'Tarih başına kontenjan tanımlanıyor; dolduğunda o tarih kapanıyor ya da yedek listeye geçiyor. Kontenjanı panelden siz yönetiyorsunuz.'],
                    ['q' => 'Tur programı değişince her seferinde size mi geleceğiz?', 'a' => 'Hayır. Saat saat program, dahil olanlar, dahil olmayanlar ve buluşma noktası panelde düzenlenebilir alanlar.'],
                    ['q' => 'Yabancı misafir için para birimi nasıl gösterilecek?', 'a' => 'İkinci bir para biriminde gösterim ekliyoruz, kuru siz belirliyorsunuz. Misafirin fiyatı anlamak için siteden çıkması, rezervasyonun en çok kaybedildiği anlardan biri.'],
                    ['q' => 'Transfer hizmetimizi de aynı sitede satabilir miyiz?', 'a' => 'Satabilirsiniz. Turlar ve transferler ayrı listeler olarak kuruluyor; transfer tarafında güzergâh, araç tipi ve kişi sayısı seçimi oluyor.'],
                ],
                'en' => [
                    ['q' => 'Can the site track tour capacity?', 'a' => 'Capacity is defined per date; when a date fills it closes or moves to a waiting list. You manage capacity from the panel.'],
                    ['q' => 'Do we have to come to you every time an itinerary changes?', 'a' => 'No. The hour-by-hour itinerary, what is included, what is not, and the meeting point are all editable fields in the panel.'],
                    ['q' => 'How are prices shown to foreign guests?', 'a' => 'We add display in a second currency, with the rate set by you. A guest leaving the site to work out the price is one of the most common points where a booking is lost.'],
                    ['q' => 'Can we sell transfers on the same site?', 'a' => 'Yes. Tours and transfers are built as separate lists, with route, vehicle type and passenger count on the transfer side.'],
                ],
            ],

            'guzellik-ve-bakim' => [
                'tr' => [
                    ['q' => 'Müşteriler zaten sosyal medyadan yazıyor, siteye ihtiyaç var mı?', 'a' => 'Sosyal medya hesabı size ait değil; erişimi platform belirliyor ve kapanma riski gerçek. Ayrıca "yakınımdaki" aramalarında çıkmanın yolu site ve işletme kaydından geçiyor, sosyal medyadan değil.'],
                    ['q' => 'Randevu sistemi kuruyor musunuz?', 'a' => 'Hizmet, personel ve saat seçmeli randevu talebi formu kuruyoruz; onayı siz veriyorsunuz. Takvimi otomatik bloke eden tam randevu yazılımı ayrı kapsam olarak değerlendiriliyor.'],
                    ['q' => 'Fiyat listesi yayınlamalı mıyız?', 'a' => 'Hizmet bazlı net fiyat verebildiğiniz yerlerde yayınlamak iki tarafa da zaman kazandırıyor. Kişiye göre değişen uygulamalarda "başlangıç fiyatı" ya da ücretsiz ön görüşme daha doğru oluyor.'],
                    ['q' => 'Öncesi-sonrası fotoğrafı koyabilir miyiz?', 'a' => 'Bu içerik bazı uygulamalarda mevzuata tabi ve kurallar hizmet türüne göre değişiyor. Teknik olarak galeri hazır; hangi görselin yayınlanabileceğine siz karar veriyorsunuz. Müşteri izni olmadan hiçbir görseli yayınlamayın.'],
                ],
                'en' => [
                    ['q' => 'Clients already message us on social media. Do we need a site?', 'a' => 'A social account is not yours; the platform controls access and the risk of losing it is real. "Near me" searches are also won through your site and business listing, not through social media.'],
                    ['q' => 'Do you build a booking system?', 'a' => 'We build an appointment request form with service, staff member and time selection, which you confirm. Full scheduling software that blocks the calendar automatically is assessed as a separate scope.'],
                    ['q' => 'Should we publish a price list?', 'a' => 'Where you can give a firm per-service price, publishing it saves both sides time. For treatments that vary by person, a "from" price or a free consultation works better.'],
                    ['q' => 'Can we publish before-and-after photos?', 'a' => 'For some treatments this content is regulated, and the rules vary by service type. The gallery is technically ready; you decide what may be published. Never publish an image without the client\'s consent.'],
                ],
            ],

            'etkinlik-ve-organizasyon' => [
                'tr' => [
                    ['q' => 'Siteden bilet satabilir miyiz?', 'a' => 'Ödeme sağlayıcısı kurulduğunda satabilirsiniz. Koltuk seçimli salon düzeni, kota yönetimi ve kapıda okutma gibi ihtiyaçlar varsa bunlar ayrı kapsam olarak konuşuluyor.'],
                    ['q' => 'Her etkinlik için ayrı site mi gerekiyor?', 'a' => 'Gerekmiyor. Tek site içinde etkinlik sayfaları açılıyor, geçmiş etkinlikler arşivde kalıyor. Büyük tek seferlik organizasyonlarda ayrı alan adı tercih edilebiliyor; bunu birlikte değerlendiriyoruz.'],
                    ['q' => 'Etkinlik geçince sayfası ne olacak?', 'a' => 'Arşivde kalıyor ve geçmiş işleriniz olarak çalışıyor. Fotoğraf galerisi eklendiğinde bu sayfalar sonraki müşteriler için en ikna edici bölüm oluyor.'],
                    ['q' => 'Katılımcı listesini sitede tutabilir miyiz?', 'a' => 'Kayıt formundan gelen katılımcılar panelde listeleniyor ve tablo olarak dışa aktarılabiliyor. Kişisel veri topladığınız için formda yalnızca gerçekten gereken alanları istemenizi öneriyoruz.'],
                ],
                'en' => [
                    ['q' => 'Can we sell tickets from the site?', 'a' => 'Once a payment provider is set up, yes. Needs like seat selection, quota management and door scanning are discussed as a separate scope.'],
                    ['q' => 'Does each event need its own site?', 'a' => 'No. Event pages live inside one site and past events stay in the archive. For large one-off productions a separate domain can make sense; we assess that together.'],
                    ['q' => 'What happens to an event page once the event is over?', 'a' => 'It stays in the archive and works as part of your portfolio. With a photo gallery added, those pages become the most persuasive section for your next client.'],
                    ['q' => 'Can we keep the attendee list on the site?', 'a' => 'Registrations are listed in the panel and can be exported as a spreadsheet. Since you are collecting personal data, we recommend asking only for the fields you genuinely need.'],
                ],
            ],

            'spor-ve-fitness' => [
                'tr' => [
                    ['q' => 'Üyelik satışını siteden yapabilir miyiz?', 'a' => 'Paket satışı ve ödeme mümkün. Üye giriş-çıkış takibi, dondurma ve otomatik yenileme gibi işletme süreçleri ayrı bir yazılım işi; sitede hangi kısmın duracağını teklif aşamasında netleştiriyoruz.'],
                    ['q' => 'Ders programı sık değişiyor, her hafta size mi geleceğiz?', 'a' => 'Hayır. Haftalık program panelden düzenleniyor; ders, saat, salon ve eğitmen alanlarını siz güncelliyorsunuz.'],
                    ['q' => 'Deneme dersi formu işe yarıyor mu?', 'a' => 'Spor salonlarında en çok dönüş alan form tipi bu. Kişiyi üyelik satın almaya değil bir kez gelmeye davet ettiği için eşiği düşürüyor.'],
                    ['q' => 'Eğitmen profilleri gerekli mi?', 'a' => 'Gerekli. Ziyaretçi salonu değil kimle çalışacağını merak ediyor. Eğitmen adı, uzmanlık alanı ve fotoğrafı olan sayfalar, yalnızca ekipman fotoğrafı olanlardan belirgin şekilde daha iyi dönüyor.'],
                ],
                'en' => [
                    ['q' => 'Can we sell memberships from the site?', 'a' => 'Package sales and payment are possible. Operational processes like check-in tracking, freezes and auto-renewal are separate software work; we agree at the quote stage which part lives on the site.'],
                    ['q' => 'Our class timetable changes often. Do we come to you every week?', 'a' => 'No. The weekly timetable is edited in the panel — you update class, time, studio and instructor yourself.'],
                    ['q' => 'Do trial-class forms actually work?', 'a' => 'It is the highest-converting form type for gyms. It lowers the barrier because it invites someone to turn up once rather than buy a membership.'],
                    ['q' => 'Do we need instructor profiles?', 'a' => 'Yes. Visitors are less curious about the gym than about who they will train with. Pages with instructor names, specialities and photos convert noticeably better than pages showing only equipment.'],
                ],
            ],

            'lojistik-ve-nakliyat' => [
                'tr' => [
                    ['q' => 'Gönderi takibi siteye konabilir mi?', 'a' => 'Kendi takip numaranızı sorgulatan bir ekran kurulabiliyor. Verinin nereden geleceği belirleyici: kendi sisteminiz varsa ona bağlanıyoruz, yoksa panelden durum güncellemesi girilen daha basit bir yapı kuruluyor.'],
                    ['q' => 'Teklif formunda ne sormalıyız?', 'a' => 'Çıkış ve varış noktası, yük tipi, yaklaşık ağırlık veya hacim ve istenen tarih. Bu beş alan olmadan gelen talebe fiyat veremiyorsunuz ve karşılıklı yazışma uzuyor.'],
                    ['q' => 'Araç filomuzu göstermeli miyiz?', 'a' => 'Kurumsal müşteri kapasitenizi görmek istiyor. Araç tipi, taşıma kapasitesi ve adet bilgisi, yalnızca "geniş filo" demekten çok daha ikna edici.'],
                    ['q' => 'Uluslararası taşıma yapıyoruz, İngilizce şart mı?', 'a' => 'Yurt dışı müşteri ya da acente ile çalışıyorsanız evet. Güzergâh, gümrük ve hizmet açıklamalarının doğru terimlerle çevrilmesi, tasarımdan daha belirleyici oluyor.'],
                ],
                'en' => [
                    ['q' => 'Can shipment tracking live on the site?', 'a' => 'A screen that looks up your own tracking number can be built. Where the data comes from decides the shape: if you have a system, we connect to it; if not, we build a simpler structure where status is updated from the panel.'],
                    ['q' => 'What should the quote form ask for?', 'a' => 'Origin and destination, cargo type, approximate weight or volume, and the required date. Without those five fields you cannot price an enquiry and the back-and-forth drags on.'],
                    ['q' => 'Should we show our fleet?', 'a' => 'Corporate customers want to see your capacity. Vehicle types, load capacity and counts are far more convincing than the phrase "large fleet".'],
                    ['q' => 'We do international haulage. Is English essential?', 'a' => 'If you work with overseas customers or agents, yes. Translating routes, customs and service descriptions with the right terminology matters more than the design.'],
                ],
            ],

            'dernek-vakif-ve-kamu' => [
                'tr' => [
                    ['q' => 'Siteden bağış toplayabilir miyiz?', 'a' => 'Online bağış için bir ödeme sağlayıcısı gerekiyor ve kurumlardan istenen belgeler ticari işletmelerden farklı olabiliyor. Sağlayıcı onayı alınana kadar banka hesabı bilgileriyle başlamak pratik bir ara çözüm.'],
                    ['q' => 'Şeffaflık için ne yayınlamalıyız?', 'a' => 'Faaliyet raporları, mali özetler ve yönetim kurulu bilgisi. Bağışçı güveni en çok burada kuruluyor; rapor arşivi olan kurumlar olmayanlardan belirgin biçimde daha kolay bağış alıyor.'],
                    ['q' => 'Üyelik başvurusu alabilir miyiz?', 'a' => 'Üyelik formu, aidat bilgisi ve onay akışı kurulabiliyor. Başvurular panelde listeleniyor, onaylama kararını siz veriyorsunuz.'],
                    ['q' => 'Erişilebilirlik konusunda ne yapıyorsunuz?', 'a' => 'Renk karşıtlığı, klavye ile gezinme ve ekran okuyucu uyumu standart olarak gözetiliyor. Resmî bir erişilebilirlik denetimi gerekiyorsa bunu ayrı bir kapsam olarak planlıyoruz.'],
                ],
                'en' => [
                    ['q' => 'Can we collect donations through the site?', 'a' => 'Online donations need a payment provider, and the documents asked of institutions can differ from those asked of businesses. Starting with bank account details is a practical interim step until provider approval comes through.'],
                    ['q' => 'What should we publish for transparency?', 'a' => 'Activity reports, financial summaries and board information. This is where donor trust is built; organisations with a report archive raise funds noticeably more easily than those without.'],
                    ['q' => 'Can we take membership applications?', 'a' => 'A membership form, dues information and an approval flow can be built. Applications are listed in the panel and you make the approval decision.'],
                    ['q' => 'What do you do about accessibility?', 'a' => 'Colour contrast, keyboard navigation and screen-reader compatibility are observed as standard. If a formal accessibility audit is required, we plan that as a separate scope.'],
                ],
            ],

        ];
    }
}
