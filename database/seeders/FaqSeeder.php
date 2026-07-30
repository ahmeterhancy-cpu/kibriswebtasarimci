<?php

namespace Database\Seeders;

use App\Models\FaqItem;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['home', 'Bir site ne kadar sürede hazır olur?', 'How long does a site take?',
                'Tek sayfalık siteler 3-6 iş günü, çok sayfalı kurumsal siteler 8-12 iş günü, e-ticaret 15-25 iş günü sürer. Süre, içeriğin (metin ve görsel) bize ulaşma hızına bağlıdır.',
                'Single-page sites take 3-6 working days, multi-page corporate sites 8-12, e-commerce 15-25. The timeline depends on how quickly you get us the content.'],
            ['home', 'Siteyi kendim güncelleyebilir miyim?', 'Can I update the site myself?',
                'Panelli paketlerde evet. Metin, görsel, blog yazısı ve ürünleri kendiniz yönetirsiniz; teslimde kullanım eğitimi veriyoruz. Panelsiz paketlerde yılda iki ücretsiz revizyon hakkınız var.',
                'Yes on panelled packages. You manage text, images, blog posts and products yourself, and we train you at handover. Panel-free packages include two free revisions per year.'],
            ['home', 'Hosting ve domain dahil mi?', 'Are hosting and domain included?',
                'Evet. SSL sertifikası, hosting ve domain ilk yıl için her pakete dahildir. İkinci yıldan itibaren yıllık yenileme ücreti alınır.',
                'Yes. SSL, hosting and domain are included for the first year in every package. From the second year onward an annual renewal fee applies.'],
            ['home', 'Ödeme nasıl yapılıyor?', 'How does payment work?',
                'Tanıtım ve kurumsal paketlerde %50 başlangıçta, %50 yayın öncesi. E-ticaret projelerinde üç taksit uygulanır.',
                'For showcase and corporate packages: 50% up front, 50% before launch. E-commerce projects are split into three instalments.'],
            ['home', 'Sadece Kıbrıs\'ta mı çalışıyorsunuz?', 'Do you only work in Cyprus?',
                'Merkezimiz Kuzey Kıbrıs ama Türkiye ve İngiltere\'deki müşterilerle de uzaktan çalışıyoruz. Süreç görüşmeleri online yürüyor.',
                'We are based in North Cyprus but also work remotely with clients in Türkiye and the UK. The process runs online.'],

            ['packages', 'Panelli ve panelsiz farkı nedir?', 'What is the difference between panelled and panel-free?',
                'Panelsiz sitede içerik sabittir; güncellemeleri biz yaparız (yılda iki ücretsiz revizyon). Panelli sitede metin, görsel, blog ve ürünleri kendiniz yönetirsiniz. İçeriği sık değişmeyen işletmeler için panelsiz daha hızlı ve ekonomiktir.',
                'On a panel-free site the content is fixed and we handle updates (two free revisions a year). With a panel you manage text, images, blog and products yourself. If your content rarely changes, panel-free is faster and cheaper.'],
            ['packages', 'Online satış istemiyorum ama ürünlerimi göstermek istiyorum.', 'I want to show products without selling online.',
                'Bu durumda e-ticarete gerek yok. Kurumsal paket + katalog modülü ile ürünlerinizi sergiler, siparişi WhatsApp üzerinden alırsınız. Katalog modülü 5.000 ₺ ek ücretlidir.',
                'You do not need e-commerce for that. Take the Corporate package plus the catalogue module: products are displayed and orders arrive over WhatsApp. The module is an extra 5,000 ₺.'],
            ['packages', 'Fiyatlara KDV dahil mi?', 'Do prices include VAT?',
                'Hayır, listelenen Kuzey Kıbrıs fiyatları KDV hariçtir.',
                'No — the listed North Cyprus prices exclude VAT.'],
            ['packages', 'Kampanya ne zamana kadar geçerli?', 'How long is the campaign valid?',
                'Kampanya 30 gün veya ilk 20 müşteri ile sınırlıdır; sonrasında normal fiyatlar geçerli olur.',
                'The campaign runs for 30 days or the first 20 clients, whichever comes first. Standard pricing applies after that.'],

            ['quote', 'Teklif ücretli mi?', 'Is the quote free?',
                'Hayır. Formu doldurmanız yeterli; 24 saat içinde kapsamı netleştiren bir teklif gönderiyoruz.',
                'No. Fill in the form and we send a scoped proposal within 24 hours.'],
            ['quote', 'Formdaki tahmini bütçe bağlayıcı mı?', 'Is the estimated budget binding?',
                'Hayır, tahmin seçimlerinize göre anlık hesaplanan bir aralıktır. Nihai fiyat görüşme sonrası netleşir.',
                'No — it is a live range calculated from your selections. The final price is agreed after we talk.'],
        ];

        foreach ($items as $i => [$page, $q, $qEn, $a, $aEn]) {
            FaqItem::query()->updateOrCreate(
                ['question' => $q],
                [
                    'question_en' => $qEn,
                    'answer' => $a,
                    'answer_en' => $aEn,
                    'page' => $page,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
