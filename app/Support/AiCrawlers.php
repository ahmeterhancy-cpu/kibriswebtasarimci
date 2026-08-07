<?php

namespace App\Support;

/**
 * Yapay zekâ tarayıcılarının tek listesi.
 *
 * İki farklı amaç var, karıştırılırsa görünürlük kaybedilir:
 *
 *  - EĞİTİM botları (GPTBot, ClaudeBot, CCBot, Google-Extended…) içeriği model
 *    eğitimi için toplar. Kapatmak görünürlüğü doğrudan düşürmez.
 *  - ARAMA/ALINTI botları (OAI-SearchBot, Claude-SearchBot, PerplexityBot…)
 *    kullanıcı bir şey sorduğunda siteyi getirip cevapta kaynak gösterir.
 *    Bunları kapatmak, ChatGPT/Perplexity cevaplarında hiç görünmemek demektir.
 *
 * Bu yüzden her botun `purpose` alanı var ve panelde ayrı ayrı işaretleniyor.
 */
class AiCrawlers
{
    /**
     * @return array<string, array{label: string, agents: array<int, string>, purpose: string, note: string}>
     */
    public static function all(): array
    {
        return [
            'openai_search' => [
                'label' => 'ChatGPT arama (OAI-SearchBot, ChatGPT-User)',
                'agents' => ['OAI-SearchBot', 'ChatGPT-User'],
                'purpose' => 'search',
                'note' => 'ChatGPT bir soruya cevap verirken siteyi getirip kaynak gösterir. Kapatmak = ChatGPT sonuçlarında yok olmak.',
            ],
            'openai_train' => [
                'label' => 'OpenAI eğitim (GPTBot)',
                'agents' => ['GPTBot'],
                'purpose' => 'train',
                'note' => 'İçeriği model eğitimi için toplar. Kapatmak arama görünürlüğünü doğrudan etkilemez.',
            ],
            'anthropic_search' => [
                'label' => 'Claude arama (Claude-SearchBot, Claude-User)',
                'agents' => ['Claude-SearchBot', 'Claude-User'],
                'purpose' => 'search',
                'note' => 'Claude cevap üretirken siteyi kaynak olarak getirir.',
            ],
            'anthropic_train' => [
                'label' => 'Anthropic eğitim (ClaudeBot)',
                'agents' => ['ClaudeBot', 'anthropic-ai'],
                'purpose' => 'train',
                'note' => 'Model eğitimi için tarama.',
            ],
            'perplexity' => [
                'label' => 'Perplexity (PerplexityBot, Perplexity-User)',
                'agents' => ['PerplexityBot', 'Perplexity-User'],
                'purpose' => 'search',
                'note' => 'Perplexity cevaplarında kaynak kartı olarak çıkmak için gerekli.',
            ],
            'google_extended' => [
                'label' => 'Google Gemini / AI Overviews (Google-Extended)',
                'agents' => ['Google-Extended'],
                'purpose' => 'train',
                'note' => 'Yalnız Gemini eğitimini ve AI Overviews alıntısını kontrol eder. Normal Google aramasını ETKİLEMEZ — Googlebot ayrıdır.',
            ],
            'applebot_extended' => [
                'label' => 'Apple Intelligence (Applebot-Extended)',
                'agents' => ['Applebot-Extended'],
                'purpose' => 'train',
                'note' => 'Apple\'ın üretken modelleri için. Siri/Spotlight aramasını etkilemez.',
            ],
            'meta' => [
                'label' => 'Meta AI (meta-externalagent)',
                'agents' => ['meta-externalagent', 'FacebookBot'],
                'purpose' => 'train',
                'note' => 'Meta\'nın yapay zekâ tarayıcısı.',
            ],
            'amazon' => [
                'label' => 'Amazon (Amazonbot)',
                'agents' => ['Amazonbot'],
                'purpose' => 'train',
                'note' => 'Alexa ve Amazon\'un modelleri için tarama.',
            ],
            'bytedance' => [
                'label' => 'ByteDance / TikTok (Bytespider)',
                'agents' => ['Bytespider'],
                'purpose' => 'train',
                'note' => 'Agresif tarar, ticari getirisi düşüktür. Sunucu yükü sorun olursa ilk kapatılacak bot budur.',
            ],
            'commoncrawl' => [
                'label' => 'Common Crawl (CCBot)',
                'agents' => ['CCBot'],
                'purpose' => 'train',
                'note' => 'Neredeyse tüm açık modellerin eğitim verisi buradan geçer. Kapatmak uzun vadede marka bilinirliğini düşürebilir.',
            ],
        ];
    }

    /** Panelde işaretlenmemişse hangi botlar varsayılan olarak açık? */
    public static function defaults(): array
    {
        // Hepsi açık: bir web tasarım stüdyosunun içeriği zaten halka açık ve
        // yapay zekâ cevaplarında görünmek doğrudan iş getiriyor.
        return array_keys(static::all());
    }
}
