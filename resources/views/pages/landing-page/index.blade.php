@extends('layouts.master')

@section('title', 'Landing Page Builder')

@section('content')
    <form method="POST" action="{{ route('landing-page.settings.update') }}" id="landingBuilderForm" class="space-y-4">
        @csrf
        <input type="hidden" name="sections_json" id="sectionsJson">

        <section class="glass-card p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Landing Page Builder</h2>
                    <p class="text-sm text-slate-500">Aktifkan/nonaktifkan section dan atur urutan dengan drag & drop.</p>
                </div>
                <a href="{{ route('landing') }}" target="_blank" class="mac-btn">Preview Landing</a>
            </div>

            <div id="sectionsList" class="mt-4 space-y-2">
                @foreach ($sections as $section)
                    <div class="section-item flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2" draggable="true" data-key="{{ $section['key'] }}">
                        <div class="flex items-center gap-3">
                            <button type="button" class="cursor-move rounded-lg border border-slate-200 px-2 py-1 text-xs text-slate-500">drag</button>
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $section['label'] }}</p>
                                <p class="text-xs text-slate-400">{{ $section['key'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <button type="button" class="move-up rounded-lg border border-slate-200 px-2 py-1 text-xs text-slate-500">up</button>
                                <button type="button" class="move-down rounded-lg border border-slate-200 px-2 py-1 text-xs text-slate-500">down</button>
                            </div>
                            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" class="section-enabled rounded border-slate-300" {{ $section['enabled'] ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-2 text-xs text-slate-400">Desktop: drag item. Mobile: gunakan tombol up/down.</p>
        </section>

        <section class="glass-card p-5">
            <h3 class="text-lg font-semibold text-slate-900">Hero</h3>
            <div class="mt-3 grid gap-3 md:grid-cols-2">
                <div>
                    <label class="mac-label">Badge</label>
                    <input class="mac-input" name="content[hero][badge]" value="{{ $content['hero']['badge'] }}">
                </div>
                <div>
                    <label class="mac-label">Title</label>
                    <input class="mac-input" name="content[hero][title]" value="{{ $content['hero']['title'] }}">
                </div>
                <div class="md:col-span-2">
                    <label class="mac-label">Subtitle</label>
                    <textarea class="mac-input" rows="2" name="content[hero][subtitle]">{{ $content['hero']['subtitle'] }}</textarea>
                </div>
                <div>
                    <label class="mac-label">Primary Button Text</label>
                    <input class="mac-input" name="content[hero][primary_text]" value="{{ $content['hero']['primary_text'] }}">
                </div>
                <div>
                    <label class="mac-label">Primary Button Link</label>
                    <input class="mac-input" name="content[hero][primary_link]" value="{{ $content['hero']['primary_link'] }}">
                </div>
                <div>
                    <label class="mac-label">Secondary Button Text</label>
                    <input class="mac-input" name="content[hero][secondary_text]" value="{{ $content['hero']['secondary_text'] }}">
                </div>
                <div>
                    <label class="mac-label">Secondary Button Link</label>
                    <input class="mac-input" name="content[hero][secondary_link]" value="{{ $content['hero']['secondary_link'] }}">
                </div>
            </div>
        </section>

        <section class="glass-card p-5">
            <h3 class="text-lg font-semibold text-slate-900">Section Content Editor</h3>
            <p class="mt-1 text-sm text-slate-500">Format multi item: satu baris satu data. Gunakan separator <code>|</code>.</p>

            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="mac-label">Trusted By Title</label>
                    <input class="mac-input" name="content[trusted_by][title]" value="{{ $content['trusted_by']['title'] }}">
                    <label class="mac-label">Trusted Logos (satu baris satu nama)</label>
                    <textarea class="mac-input" rows="4" name="trusted_by_logos_text">{{ $editor['trusted_by_logos_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">About Title</label>
                    <input class="mac-input" name="content[about][title]" value="{{ $content['about']['title'] }}">
                    <label class="mac-label">About Text</label>
                    <textarea class="mac-input" rows="3" name="content[about][text]">{{ $content['about']['text'] }}</textarea>
                    <label class="mac-label">About Highlight</label>
                    <input class="mac-input" name="content[about][highlight]" value="{{ $content['about']['highlight'] }}">
                </div>

                <div class="space-y-2">
                    <label class="mac-label">Services Title</label>
                    <input class="mac-input" name="content[services][title]" value="{{ $content['services']['title'] }}">
                    <label class="mac-label">Services Items (Title|Description)</label>
                    <textarea class="mac-input" rows="5" name="services_items_text">{{ $editor['services_items_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">Features Title</label>
                    <input class="mac-input" name="content[features][title]" value="{{ $content['features']['title'] }}">
                    <label class="mac-label">Features Items (Title|Description)</label>
                    <textarea class="mac-input" rows="5" name="features_items_text">{{ $editor['features_items_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">Portfolio Title</label>
                    <input class="mac-input" name="content[portfolio][title]" value="{{ $content['portfolio']['title'] }}">
                    <label class="mac-label">Portfolio Items (Title|Result)</label>
                    <textarea class="mac-input" rows="5" name="portfolio_items_text">{{ $editor['portfolio_items_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">Testimonials Title</label>
                    <input class="mac-input" name="content[testimonials][title]" value="{{ $content['testimonials']['title'] }}">
                    <label class="mac-label">Testimonials (Name|Role|Quote)</label>
                    <textarea class="mac-input" rows="5" name="testimonials_items_text">{{ $editor['testimonials_items_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">Pricing Title</label>
                    <input class="mac-input" name="content[pricing][title]" value="{{ $content['pricing']['title'] }}">
                    <label class="mac-label">Pricing (Plan|Price|Feature1,Feature2,Feature3)</label>
                    <textarea class="mac-input" rows="5" name="pricing_items_text">{{ $editor['pricing_items_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">FAQ Title</label>
                    <input class="mac-input" name="content[faq][title]" value="{{ $content['faq']['title'] }}">
                    <label class="mac-label">FAQ Items (Question|Answer)</label>
                    <textarea class="mac-input" rows="5" name="faq_items_text">{{ $editor['faq_items_text'] }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="mac-label">CTA Title</label>
                    <input class="mac-input" name="content[cta][title]" value="{{ $content['cta']['title'] }}">
                    <label class="mac-label">CTA Text</label>
                    <textarea class="mac-input" rows="2" name="content[cta][text]">{{ $content['cta']['text'] }}</textarea>
                    <label class="mac-label">CTA Button Text</label>
                    <input class="mac-input" name="content[cta][button_text]" value="{{ $content['cta']['button_text'] }}">
                    <label class="mac-label">CTA Button Link</label>
                    <input class="mac-input" name="content[cta][button_link]" value="{{ $content['cta']['button_link'] }}">
                </div>

                <div class="space-y-2">
                    <label class="mac-label">Contact Title</label>
                    <input class="mac-input" name="content[contact][title]" value="{{ $content['contact']['title'] }}">
                    <label class="mac-label">Contact Email</label>
                    <input class="mac-input" name="content[contact][email]" value="{{ $content['contact']['email'] }}">
                    <label class="mac-label">Contact Phone</label>
                    <input class="mac-input" name="content[contact][phone]" value="{{ $content['contact']['phone'] }}">
                    <label class="mac-label">Contact Address</label>
                    <input class="mac-input" name="content[contact][address]" value="{{ $content['contact']['address'] }}">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="mac-label">Footer Text</label>
                    <textarea class="mac-input" rows="2" name="content[footer][text]">{{ $content['footer']['text'] }}</textarea>
                    <label class="mac-label">Footer Links (Label|URL)</label>
                    <textarea class="mac-input" rows="4" name="footer_links_text">{{ $editor['footer_links_text'] }}</textarea>
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="mac-btn-primary">Save Landing Page</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const sectionsList = document.getElementById('sectionsList');
        const form = document.getElementById('landingBuilderForm');
        const sectionsJson = document.getElementById('sectionsJson');

        let dragItem = null;

        sectionsList.querySelectorAll('.section-item').forEach((item) => {
            item.addEventListener('dragstart', () => {
                dragItem = item;
                item.classList.add('opacity-60');
            });

            item.addEventListener('dragend', () => {
                item.classList.remove('opacity-60');
                dragItem = null;
            });

            item.addEventListener('dragover', (event) => {
                event.preventDefault();
                if (!dragItem || dragItem === item) return;
                const rect = item.getBoundingClientRect();
                const isAfter = event.clientY > rect.top + rect.height / 2;
                if (isAfter) {
                    item.after(dragItem);
                } else {
                    item.before(dragItem);
                }
            });

            item.querySelector('.move-up')?.addEventListener('click', () => {
                const prev = item.previousElementSibling;
                if (prev) {
                    prev.before(item);
                }
            });

            item.querySelector('.move-down')?.addEventListener('click', () => {
                const next = item.nextElementSibling;
                if (next) {
                    next.after(item);
                }
            });
        });

        form.addEventListener('submit', () => {
            const payload = Array.from(sectionsList.querySelectorAll('.section-item')).map((item) => ({
                key: item.dataset.key,
                enabled: item.querySelector('.section-enabled').checked,
            }));

            sectionsJson.value = JSON.stringify(payload);
        });
    </script>
@endpush
