@extends('layouts.master')
@section('title', 'Logs') <!-- Menambahkan title untuk halaman ini -->
@section('content')
    <div class="row justify-content-center">

        <div class="col-md-12">
            <h4>Daftar Log</h4>

            <!-- Menampilkan pesan sukses atau error -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Filter dan Form Pencarian -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Show Entries -->
                <div class="d-flex align-items-center">
                    <label for="entries_per_page" class="me-2 mb-0">Show</label>
                    <form method="GET" class="d-inline">
                        <select name="entries_per_page" id="entries_per_page" class="form-select d-inline w-auto"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('entries_per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries_per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries_per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('entries_per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="250" {{ request('entries_per_page') == 250 ? 'selected' : '' }}>250</option>
                            <option value="1" {{ request('entries_per_page') == 1 ? 'selected' : '' }}>1</option>
                        </select>
                        <span class="ms-2">entries per page</span>
                    </form>
                </div>

                <!-- Filter berdasarkan Created At -->
                <div class="d-flex align-items-center">
                    <form method="GET" class="d-flex">
                        <label for="start_date" class="form-label me-2 mb-0">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                            class="form-control me-2">

                        <label for="end_date" class="form-label me-2 mb-0">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                            class="form-control me-2">

                        <button type="submit" class="btn btn-primary ms-2">Filter</button>
                    </form>
                </div>

                <!-- Search Form -->
                <div class="d-flex align-items-center">
                    <form method="GET" class="d-flex">
                        <!-- Dropdown Pencarian Berdasarkan -->
                        <select name="search_by" class="form-select me-2" onchange="this.form.submit()">
                            <option value="action" {{ request('search_by') == 'action' ? 'selected' : '' }}>Action</option>
                            <option value="description" {{ request('search_by') == 'description' ? 'selected' : '' }}>
                                Description
                            </option>
                            <option value="category" {{ request('search_by') == 'category' ? 'selected' : '' }}>Category
                            </option>
                            <option value="user_agent" {{ request('search_by') == 'user_agent' ? 'selected' : '' }}>User
                                Agent
                            </option>
                            <option value="external_id" {{ request('search_by') == 'external_id' ? 'selected' : '' }}>
                                External
                                ID</option>
                            <option value="ip_address" {{ request('search_by') == 'ip_address' ? 'selected' : '' }}>IP
                                Address
                            </option>
                        </select>


                        <!-- Field Pencarian -->
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control ms-2"
                            placeholder="Search..." aria-label="Search">

                        <!-- Tombol Search (Submit) dengan Icon -->
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="bi bi-search"></i> <!-- Bootstrap Icon Search -->
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar Log -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No </th>
                        <th>
                            <a href="{{ route('logs.index', ['sort_by' => 'user_id', 'sort_order' => request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                style="text-decoration: none;">
                                User
                                @if (request('sort_by') == 'user_id')
                                    <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('logs.index', ['sort_by' => 'role_name', 'sort_order' => request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                style="text-decoration: none;">
                                Role
                                @if (request('sort_by') == 'role_name')
                                    <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                                @endif
                            </a>
                        </th>

                        <th>
                            <a href="{{ route('logs.index', ['sort_by' => 'action', 'sort_order' => request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                style="text-decoration: none;">
                                Action
                                @if (request('sort_by') == 'action')
                                    <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('logs.index', ['sort_by' => 'description', 'sort_order' => request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                style="text-decoration: none;">
                                Description
                                @if (request('sort_by') == 'description')
                                    <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('logs.index', ['sort_by' => 'category', 'sort_order' => request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                style="text-decoration: none;">
                                Category
                                @if (request('sort_by') == 'category')
                                    <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('logs.index', ['sort_by' => 'created_at', 'sort_order' => request('sort_order', 'asc') == 'asc' ? 'desc' : 'asc']) }}"
                                style="text-decoration: none;">
                                Created At
                                @if (request('sort_by') == 'created_at')
                                    <i class="bi bi-arrow-{{ request('sort_order', 'asc') == 'asc' ? 'down' : 'up' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>User Agent</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td> <!-- Menampilkan no urut -->
                            <td>{{ $log->user ? $log->user->name : 'Unknown' }}</td>
                            <td>
                                @if ($log->user && $log->user->roles->isNotEmpty())
                                    {{ $log->user->roles->first()->name }}
                                @else
                                    Unknown
                                @endif
                            </td> <!-- Menampilkan Role -->
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->category }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->user_agent }}</td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-between">
                <div>
                    <span>Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }}
                        entries</span>
                </div>
                @if ($logs->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mt-3">
                            {{-- Previous Page Link --}}
                            <li class="page-item {{ $logs->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link"
                                    href="{{ $logs->previousPageUrl() }}&entries_per_page={{ request('entries_per_page') }}"
                                    aria-label="Previous">
                                    &laquo; Previous
                                </a>
                            </li>

                            {{-- Page Links (4 pages consistently) --}}
                            @php
                                $start = max($logs->currentPage() - 1, 1); // Start from one page before the current page, but not less than 1
                                $end = min($start + 3, $logs->lastPage()); // Show 4 pages, but don't go beyond the last page
                            @endphp

                            {{-- First Page Link if necessary --}}
                            @if ($start > 1)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $logs->url(1) }}&entries_per_page={{ request('entries_per_page') }}">1</a>
                                </li>
                                @if ($start > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- Loop through pages in the range --}}
                            @for ($page = $start; $page <= $end; $page++)
                                <li class="page-item {{ $logs->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link"
                                        href="{{ $logs->url($page) }}&entries_per_page={{ request('entries_per_page') }}">{{ $page }}</a>
                                </li>
                            @endfor

                            {{-- Last Page Link if necessary --}}
                            @if ($end < $logs->lastPage())
                                @if ($end < $logs->lastPage() - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $logs->url($logs->lastPage()) }}&entries_per_page={{ request('entries_per_page') }}">{{ $logs->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Next Page Link --}}
                            <li class="page-item {{ $logs->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link"
                                    href="{{ $logs->nextPageUrl() }}&entries_per_page={{ request('entries_per_page') }}"
                                    aria-label="Next">
                                    Next &raquo;
                                </a>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
