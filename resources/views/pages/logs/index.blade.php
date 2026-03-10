@extends('layouts.master')

@section('title', 'Logs')

@section('content')
    <section class="glass-card p-5">
        <h2 class="text-lg font-semibold text-slate-900">Activity Logs</h2>
        <p class="mt-1 text-sm text-slate-500">Lacak aktivitas sistem dan pengguna.</p>

        <form method="GET" class="mt-4 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 md:grid-cols-6">
            <div>
                <label class="mac-label">Entries</label>
                <select name="entries_per_page" class="mac-input">
                    @foreach ([10, 25, 50, 100, 250] as $entry)
                        <option value="{{ $entry }}" {{ request('entries_per_page', 10) == $entry ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mac-label">Start</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="mac-input">
            </div>
            <div>
                <label class="mac-label">End</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="mac-input">
            </div>
            <div>
                <label class="mac-label">Search By</label>
                <select name="search_by" class="mac-input">
                    @foreach (['action', 'description', 'category', 'user_agent', 'external_id', 'ip_address'] as $column)
                        <option value="{{ $column }}" {{ request('search_by', 'action') === $column ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $column)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="mac-label">Keyword</label>
                <div class="flex gap-2">
                    <input name="search" value="{{ request('search') }}" class="mac-input" placeholder="Cari log...">
                    <button type="submit" class="mac-btn-primary">Filter</button>
                    <a href="{{ route('logs.index') }}" class="mac-btn">Reset</a>
                </div>
            </div>
        </form>

        <div class="mac-table-wrap mt-4">
            <table class="mac-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><a class="hover:text-slate-900" href="{{ request()->fullUrlWithQuery(['sort_by' => 'user_id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">User</a></th>
                        <th>Role</th>
                        <th><a class="hover:text-slate-900" href="{{ request()->fullUrlWithQuery(['sort_by' => 'action', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">Action</a></th>
                        <th>Description</th>
                        <th><a class="hover:text-slate-900" href="{{ request()->fullUrlWithQuery(['sort_by' => 'category', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">Category</a></th>
                        <th><a class="hover:text-slate-900" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}">Created</a></th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $index => $log)
                        <tr>
                            <td>{{ ($logs->firstItem() ?? 1) + $index }}</td>
                            <td>{{ $log->user?->name ?? 'Unknown' }}</td>
                            <td>{{ $log->user?->roles?->first()?->name ?? '-' }}</td>
                            <td>{{ $log->action }}</td>
                            <td class="max-w-xs truncate" title="{{ $log->description }}">{{ $log->description }}</td>
                            <td>{{ $log->category }}</td>
                            <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
            <p>Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries</p>
            <div>{{ $logs->appends(request()->query())->links() }}</div>
        </div>
    </section>
@endsection
