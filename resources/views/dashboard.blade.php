@extends('layouts.master')

@section('title', __('ui.dashboard'))

@section('content')
    @php
        $teachers = [
            ['name' => 'Prof. David', 'hours' => '4 ' . __('ui.hours_lecture'), 'rate' => '$100/hr'],
            ['name' => 'Prof. Lily', 'hours' => '2 ' . __('ui.hours_lecture'), 'rate' => '$120/hr'],
            ['name' => 'Prof. Alex', 'hours' => '4 ' . __('ui.hours_lecture'), 'rate' => '$150/hr'],
        ];

        $courses = [
            ['name' => 'English', 'hours' => '20 ' . __('ui.hours')],
            ['name' => 'Spoken Course', 'hours' => '40 ' . __('ui.hours')],
            ['name' => 'Writing Course', 'hours' => '20 ' . __('ui.hours')],
            ['name' => 'Language Course', 'hours' => '20 ' . __('ui.hours')],
        ];

        $schedule = [
            ['date' => '12', 'month' => 'Dec', 'day' => 'Monday', 'time' => '10:00am-12:00pm', 'active' => false],
            ['date' => '13', 'month' => 'Dec', 'day' => 'Tuesday', 'time' => '02:00pm-04:00pm', 'active' => true],
            ['date' => '14', 'month' => 'Dec', 'day' => 'Wednesday', 'time' => '08:00am-10:00am', 'active' => false],
        ];
    @endphp

    {{-- <div class="grid gap-4 xl:grid-cols-[1.85fr,1fr]">
        <section class="neo-panel">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-2xl font-semibold text-slate-900">{{ __('ui.find_teacher') }}</h2>
                <button class="mac-btn text-xs">English</button>
            </div>

            <div class="space-y-2">
                @foreach ($teachers as $index => $teacher)
                    <article class="teacher-row {{ $index === 1 ? 'teacher-row-muted' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-full bg-slate-200"></div>
                            <p class="font-semibold text-slate-800">{{ $teacher['name'] }}</p>
                        </div>
                        <p class="text-xs text-slate-500 md:text-sm">{{ $teacher['hours'] }}</p>
                        <p class="text-xs font-medium text-slate-600 md:text-sm">{{ $teacher['rate'] }}</p>
                        <button class="text-xl leading-none text-slate-600">&bull;</button>
                    </article>
                @endforeach
            </div>

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-3xl font-semibold text-slate-900">{{ __('ui.schedule') }}</h3>
                <div class="flex items-center gap-2">
                    <p class="text-xs text-slate-500 md:text-sm">{{ now()->format('M d, Y') }}</p>
                    <button class="rounded-full bg-black px-4 py-2 text-xs font-medium text-white">Prof Lily</button>
                </div>
            </div>

            <div class="mt-3 grid gap-3 lg:grid-cols-[220px,1fr]">
                <article class="rounded-3xl border border-slate-200 bg-[#f7f8fb] p-4 text-center">
                    <div class="mx-auto h-20 w-20 rounded-full bg-slate-200"></div>
                    <p class="mt-3 text-lg font-semibold text-slate-900">Prof. Lily</p>
                    <p class="mt-2 text-sm text-slate-500">5 years experience</p>
                    <p class="text-sm text-slate-500">Master's in language</p>
                    <button class="mt-4 rounded-full bg-black px-4 py-2 text-sm font-medium text-white">{{ __('ui.book_online') }}</button>
                </article>

                <div class="space-y-3">
                    @foreach ($schedule as $item)
                        <div class="grid items-center gap-2 md:grid-cols-[160px,1fr]">
                            <div class="rounded-2xl border px-4 py-3 {{ $item['active'] ? 'border-black bg-black text-white' : 'border-slate-300 bg-white text-slate-800' }}">
                                <div class="flex items-center gap-4">
                                    <p class="text-3xl font-semibold">{{ $item['date'] }}</p>
                                    <div>
                                        <p class="text-sm">{{ $item['month'] }}</p>
                                        <p class="font-semibold">{{ $item['day'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-slate-500">{{ $item['time'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <aside class="neo-panel bg-[#eceff5]">
            <h3 class="text-3xl font-semibold text-slate-900">{{ __('ui.my_courses') }}</h3>
            <div class="mt-3 space-y-1">
                @foreach ($courses as $course)
                    <article class="course-item rounded-xl bg-white/50">
                        <div class="flex items-start gap-3">
                            <div class="grid h-8 w-8 place-items-center rounded-full bg-black text-xs text-white">{{ substr($course['name'], 0, 1) }}</div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $course['name'] }}</p>
                                <p class="text-xs text-slate-500">{{ $course['hours'] }}</p>
                            </div>
                        </div>
                        <button class="text-lg text-slate-500">&bull;</button>
                    </article>
                @endforeach
            </div>

            <div class="mt-5 rounded-3xl border border-slate-200 bg-white/70 p-4">
                <h4 class="text-2xl font-semibold text-slate-900">{{ __('ui.account_progress') }}</h4>
                <div class="mt-4">
                    <div class="progress-ring">
                        <span>70%</span>
                    </div>
                </div>
                <p class="mt-4 text-sm font-medium text-slate-700">{{ __('ui.progress') }}</p>
                <div class="mt-2 h-2 w-full rounded-full bg-slate-200">
                    <div class="h-2 w-[70%] rounded-full bg-black"></div>
                </div>
            </div>
        </aside>
    </div> --}}
@endsection
