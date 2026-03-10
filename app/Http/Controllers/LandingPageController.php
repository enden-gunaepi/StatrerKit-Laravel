<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LandingPageController extends Controller
{
    public function show()
    {
        [$sections, $content] = $this->loadConfiguration();

        $activeSections = collect($sections)
            ->filter(fn ($section) => !empty($section['enabled']))
            ->values()
            ->all();

        return view('welcome', [
            'sections' => $activeSections,
            'content' => $content,
        ]);
    }

    public function settings()
    {
        $this->authorizeSettingsAccess();

        [$sections, $content] = $this->loadConfiguration();

        return view('pages.landing-page.index', [
            'sections' => $sections,
            'content' => $content,
            'editor' => $this->buildEditorPayload($content),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeSettingsAccess();

        $sections = $this->normalizeSections(json_decode((string) $request->input('sections_json', ''), true));

        $content = $this->defaultContent();
        $content['hero']['badge'] = (string) $request->input('content.hero.badge', $content['hero']['badge']);
        $content['hero']['title'] = (string) $request->input('content.hero.title', $content['hero']['title']);
        $content['hero']['subtitle'] = (string) $request->input('content.hero.subtitle', $content['hero']['subtitle']);
        $content['hero']['primary_text'] = (string) $request->input('content.hero.primary_text', $content['hero']['primary_text']);
        $content['hero']['primary_link'] = (string) $request->input('content.hero.primary_link', $content['hero']['primary_link']);
        $content['hero']['secondary_text'] = (string) $request->input('content.hero.secondary_text', $content['hero']['secondary_text']);
        $content['hero']['secondary_link'] = (string) $request->input('content.hero.secondary_link', $content['hero']['secondary_link']);

        $content['trusted_by']['title'] = (string) $request->input('content.trusted_by.title', $content['trusted_by']['title']);
        $content['trusted_by']['logos'] = $this->parseSimpleLines((string) $request->input('trusted_by_logos_text', ''));

        $content['about']['title'] = (string) $request->input('content.about.title', $content['about']['title']);
        $content['about']['text'] = (string) $request->input('content.about.text', $content['about']['text']);
        $content['about']['highlight'] = (string) $request->input('content.about.highlight', $content['about']['highlight']);

        $content['services']['title'] = (string) $request->input('content.services.title', $content['services']['title']);
        $content['services']['items'] = $this->parseTitleDescLines((string) $request->input('services_items_text', ''));

        $content['features']['title'] = (string) $request->input('content.features.title', $content['features']['title']);
        $content['features']['items'] = $this->parseTitleDescLines((string) $request->input('features_items_text', ''));

        $content['portfolio']['title'] = (string) $request->input('content.portfolio.title', $content['portfolio']['title']);
        $content['portfolio']['items'] = $this->parseTitleDescLines((string) $request->input('portfolio_items_text', ''));

        $content['testimonials']['title'] = (string) $request->input('content.testimonials.title', $content['testimonials']['title']);
        $content['testimonials']['items'] = $this->parseTestimonials((string) $request->input('testimonials_items_text', ''));

        $content['pricing']['title'] = (string) $request->input('content.pricing.title', $content['pricing']['title']);
        $content['pricing']['items'] = $this->parsePricing((string) $request->input('pricing_items_text', ''));

        $content['faq']['title'] = (string) $request->input('content.faq.title', $content['faq']['title']);
        $content['faq']['items'] = $this->parseFaq((string) $request->input('faq_items_text', ''));

        $content['cta']['title'] = (string) $request->input('content.cta.title', $content['cta']['title']);
        $content['cta']['text'] = (string) $request->input('content.cta.text', $content['cta']['text']);
        $content['cta']['button_text'] = (string) $request->input('content.cta.button_text', $content['cta']['button_text']);
        $content['cta']['button_link'] = (string) $request->input('content.cta.button_link', $content['cta']['button_link']);

        $content['contact']['title'] = (string) $request->input('content.contact.title', $content['contact']['title']);
        $content['contact']['email'] = (string) $request->input('content.contact.email', $content['contact']['email']);
        $content['contact']['phone'] = (string) $request->input('content.contact.phone', $content['contact']['phone']);
        $content['contact']['address'] = (string) $request->input('content.contact.address', $content['contact']['address']);

        $content['footer']['text'] = (string) $request->input('content.footer.text', $content['footer']['text']);
        $content['footer']['links'] = $this->parseFooterLinks((string) $request->input('footer_links_text', ''));

        Setting::updateOrCreate(['key' => 'landing_sections'], ['value' => json_encode($sections, JSON_UNESCAPED_UNICODE)]);
        Setting::updateOrCreate(['key' => 'landing_content'], ['value' => json_encode($content, JSON_UNESCAPED_UNICODE)]);

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'landing_page_settings_updated',
            'category' => 'settings_update',
            'description' => 'Landing page sections and content updated.',
            'user_agent' => $request->header('User-Agent'),
            'external_id' => null,
        ]);

        return redirect()->route('landing-page.settings')->with('success', 'Landing page settings updated.');
    }

    private function authorizeSettingsAccess(): void
    {
        if (auth()->check() && Gate::denies('has-permission', 'view-settings')) {
            abort(403, 'Akses dilarang');
        }
    }

    private function loadConfiguration(): array
    {
        $defaultSections = $this->defaultSections();
        $storedSections = json_decode((string) Setting::where('key', 'landing_sections')->value('value'), true);
        $sections = $this->normalizeSections($storedSections ?: $defaultSections);

        $storedContent = json_decode((string) Setting::where('key', 'landing_content')->value('value'), true);
        $content = $this->mergeRecursiveDistinct($this->defaultContent(), is_array($storedContent) ? $storedContent : []);

        return [$sections, $content];
    }

    private function normalizeSections(?array $sections): array
    {
        $defaults = collect($this->defaultSections())->keyBy('key');
        $normalized = [];

        foreach ((array) $sections as $section) {
            if (!is_array($section) || empty($section['key']) || !$defaults->has($section['key'])) {
                continue;
            }

            $default = $defaults->get($section['key']);
            $normalized[] = [
                'key' => $default['key'],
                'label' => $default['label'],
                'enabled' => isset($section['enabled']) ? (bool) $section['enabled'] : (bool) $default['enabled'],
            ];
        }

        foreach ($defaults as $key => $default) {
            if (!collect($normalized)->contains(fn ($item) => $item['key'] === $key)) {
                $normalized[] = $default;
            }
        }

        return array_values($normalized);
    }

    private function defaultSections(): array
    {
        return [
            ['key' => 'hero', 'label' => 'Hero', 'enabled' => true],
            ['key' => 'trusted_by', 'label' => 'Trusted by / Client logos', 'enabled' => true],
            ['key' => 'about', 'label' => 'About', 'enabled' => true],
            ['key' => 'services', 'label' => 'Services', 'enabled' => true],
            ['key' => 'features', 'label' => 'Features', 'enabled' => true],
            ['key' => 'portfolio', 'label' => 'Portfolio / Case Study', 'enabled' => true],
            ['key' => 'testimonials', 'label' => 'Testimonials', 'enabled' => true],
            ['key' => 'pricing', 'label' => 'Pricing', 'enabled' => true],
            ['key' => 'faq', 'label' => 'FAQ', 'enabled' => true],
            ['key' => 'cta', 'label' => 'CTA', 'enabled' => true],
            ['key' => 'contact', 'label' => 'Contact', 'enabled' => true],
            ['key' => 'footer', 'label' => 'Footer', 'enabled' => true],
        ];
    }

    private function defaultContent(): array
    {
        return [
            'hero' => [
                'badge' => 'Premium Digital Studio',
                'title' => 'Build a bold digital presence with elegant execution.',
                'subtitle' => 'We craft strategic websites and experiences for modern brands.',
                'primary_text' => 'Start a Project',
                'primary_link' => '#contact',
                'secondary_text' => 'View Portfolio',
                'secondary_link' => '#portfolio',
            ],
            'trusted_by' => [
                'title' => 'Trusted by',
                'logos' => ['NEXORA', 'LUMIERE', 'NOVA', 'BLACKWAVE', 'VANTA'],
            ],
            'about' => [
                'title' => 'About Us',
                'text' => 'We are a multidisciplinary team focused on premium digital outcomes.',
                'highlight' => '10+ years of design and engineering excellence.',
            ],
            'services' => [
                'title' => 'Services',
                'items' => [
                    ['title' => 'Brand Strategy', 'description' => 'Positioning, identity systems, and market narratives.'],
                    ['title' => 'Web Design', 'description' => 'Elegant interfaces with modern UX and conversion focus.'],
                    ['title' => 'Development', 'description' => 'Fast, secure, and scalable web implementation.'],
                ],
            ],
            'features' => [
                'title' => 'Features',
                'items' => [
                    ['title' => 'Pixel-level Craft', 'description' => 'Refined visual quality with functional precision.'],
                    ['title' => 'Performance First', 'description' => 'Optimized architecture and delivery.'],
                    ['title' => 'Maintainable Build', 'description' => 'Clean foundation for future growth.'],
                ],
            ],
            'portfolio' => [
                'title' => 'Portfolio',
                'items' => [
                    ['title' => 'Luxury Retail Redesign', 'description' => 'Increased conversion by 41%.'],
                    ['title' => 'SaaS Product Website', 'description' => 'Launched in 5 weeks with design system.'],
                    ['title' => 'Corporate Rebranding', 'description' => 'Unified all channels under one visual language.'],
                ],
            ],
            'testimonials' => [
                'title' => 'Testimonials',
                'items' => [
                    ['name' => 'Evelyn Carter', 'role' => 'CMO, NEXORA', 'quote' => 'The final product felt premium from day one.'],
                    ['name' => 'Andre Hill', 'role' => 'Founder, LUMIERE', 'quote' => 'Execution quality and communication were exceptional.'],
                ],
            ],
            'pricing' => [
                'title' => 'Pricing',
                'items' => [
                    ['plan' => 'Starter', 'price' => '$1,500', 'features' => ['Landing page', 'Basic SEO', '2 revisions']],
                    ['plan' => 'Growth', 'price' => '$3,900', 'features' => ['Multi-page website', 'CMS setup', 'Priority support']],
                    ['plan' => 'Premium', 'price' => '$8,500', 'features' => ['Custom experience', 'Advanced integrations', 'Dedicated team']],
                ],
            ],
            'faq' => [
                'title' => 'FAQ',
                'items' => [
                    ['question' => 'How long does a project take?', 'answer' => 'Most projects are delivered within 3-8 weeks depending on scope.'],
                    ['question' => 'Do you provide ongoing support?', 'answer' => 'Yes, we provide maintenance and iterative improvement packages.'],
                ],
            ],
            'cta' => [
                'title' => 'Ready to launch your next digital product?',
                'text' => 'Let us turn your idea into a premium online experience.',
                'button_text' => 'Book a Consultation',
                'button_link' => '#contact',
            ],
            'contact' => [
                'title' => 'Contact',
                'email' => 'hello@yourstudio.com',
                'phone' => '+62 812 0000 0000',
                'address' => 'Jakarta, Indonesia',
            ],
            'footer' => [
                'text' => 'Premium digital studio for ambitious brands.',
                'links' => [
                    ['label' => 'Privacy', 'url' => '#'],
                    ['label' => 'Terms', 'url' => '#'],
                    ['label' => 'Instagram', 'url' => '#'],
                ],
            ],
        ];
    }

    private function buildEditorPayload(array $content): array
    {
        return [
            'trusted_by_logos_text' => implode("\n", $content['trusted_by']['logos'] ?? []),
            'services_items_text' => collect($content['services']['items'] ?? [])->map(
                fn ($item) => ($item['title'] ?? '') . '|' . ($item['description'] ?? '')
            )->implode("\n"),
            'features_items_text' => collect($content['features']['items'] ?? [])->map(
                fn ($item) => ($item['title'] ?? '') . '|' . ($item['description'] ?? '')
            )->implode("\n"),
            'portfolio_items_text' => collect($content['portfolio']['items'] ?? [])->map(
                fn ($item) => ($item['title'] ?? '') . '|' . ($item['description'] ?? '')
            )->implode("\n"),
            'testimonials_items_text' => collect($content['testimonials']['items'] ?? [])->map(
                fn ($item) => ($item['name'] ?? '') . '|' . ($item['role'] ?? '') . '|' . ($item['quote'] ?? '')
            )->implode("\n"),
            'pricing_items_text' => collect($content['pricing']['items'] ?? [])->map(
                fn ($item) => ($item['plan'] ?? '') . '|' . ($item['price'] ?? '') . '|' . implode(',', $item['features'] ?? [])
            )->implode("\n"),
            'faq_items_text' => collect($content['faq']['items'] ?? [])->map(
                fn ($item) => ($item['question'] ?? '') . '|' . ($item['answer'] ?? '')
            )->implode("\n"),
            'footer_links_text' => collect($content['footer']['links'] ?? [])->map(
                fn ($item) => ($item['label'] ?? '') . '|' . ($item['url'] ?? '')
            )->implode("\n"),
        ];
    }

    private function parseSimpleLines(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->values()
            ->all();
    }

    private function parseTitleDescLines(string $text): array
    {
        return collect($this->parseSimpleLines($text))->map(function ($line) {
            [$title, $description] = array_pad(explode('|', $line, 2), 2, '');
            return ['title' => trim($title), 'description' => trim($description)];
        })->filter(fn ($item) => $item['title'] !== '')
            ->values()
            ->all();
    }

    private function parseTestimonials(string $text): array
    {
        return collect($this->parseSimpleLines($text))->map(function ($line) {
            [$name, $role, $quote] = array_pad(explode('|', $line, 3), 3, '');
            return ['name' => trim($name), 'role' => trim($role), 'quote' => trim($quote)];
        })->filter(fn ($item) => $item['name'] !== '')
            ->values()
            ->all();
    }

    private function parsePricing(string $text): array
    {
        return collect($this->parseSimpleLines($text))->map(function ($line) {
            [$plan, $price, $features] = array_pad(explode('|', $line, 3), 3, '');
            return [
                'plan' => trim($plan),
                'price' => trim($price),
                'features' => collect(explode(',', (string) $features))
                    ->map(fn ($feature) => trim($feature))
                    ->filter()
                    ->values()
                    ->all(),
            ];
        })->filter(fn ($item) => $item['plan'] !== '')
            ->values()
            ->all();
    }

    private function parseFaq(string $text): array
    {
        return collect($this->parseSimpleLines($text))->map(function ($line) {
            [$question, $answer] = array_pad(explode('|', $line, 2), 2, '');
            return ['question' => trim($question), 'answer' => trim($answer)];
        })->filter(fn ($item) => $item['question'] !== '')
            ->values()
            ->all();
    }

    private function parseFooterLinks(string $text): array
    {
        return collect($this->parseSimpleLines($text))->map(function ($line) {
            [$label, $url] = array_pad(explode('|', $line, 2), 2, '#');
            return ['label' => trim($label), 'url' => trim($url) ?: '#'];
        })->filter(fn ($item) => $item['label'] !== '')
            ->values()
            ->all();
    }

    private function mergeRecursiveDistinct(array $default, array $custom): array
    {
        foreach ($custom as $key => $value) {
            if (is_array($value) && isset($default[$key]) && is_array($default[$key])) {
                $default[$key] = $this->mergeRecursiveDistinct($default[$key], $value);
            } else {
                $default[$key] = $value;
            }
        }

        return $default;
    }
}

