<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Gate;


class LogController extends Controller
{
    // Menampilkan daftar log dengan pagination
    public function index(Request $request)
    {
        if (Gate::denies('has-permission', 'view-log')) {
            abort(403, 'Akses dilarang');
        }
        // Membuat query dasar untuk Log
        $query = Log::query();

        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Hanya filter berdasarkan user_id jika pengguna memiliki izin "log-by-user" dan bukan role_id 1
        if (Gate::allows('has-permission', 'log-by-user') && !auth()->user()->roles->pluck('id')->contains(1)) {
            $query->where('logs.user_id', auth()->id());
        }



        // Filter pencarian berdasarkan kolom tertentu
        $search = $request->input('search');
        $searchBy = $request->input('search_by'); // Misalnya: 'name', 'email', 'is_active'

        if ($search) {
            if ($searchBy === 'name') {
                // Pencarian berdasarkan name user terkait
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            } elseif ($searchBy === 'email') {
                // Pencarian berdasarkan email user terkait
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', '%' . $search . '%');
                });
            } elseif ($searchBy === 'is_active') {
                // Pencarian berdasarkan status aktif (active/not active)
                $query->where('is_active', $search === 'active' ? 1 : 0);
            } elseif ($searchBy === 'action') {
                // Pencarian berdasarkan kolom action di logs
                $query->where('action', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'description') {
                // Pencarian berdasarkan kolom description di logs
                $query->where('description', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'user_agent') {
                // Pencarian berdasarkan kolom user_agent di logs
                $query->where('user_agent', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'external_id') {
                // Pencarian berdasarkan kolom external_id di logs
                $query->where('external_id', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'category') {
                // Pencarian berdasarkan kolom category di logs
                $query->where('category', 'like', '%' . $search . '%');
            } elseif ($searchBy === 'ip_address') {
                // Pencarian berdasarkan kolom ip_address di logs
                $query->where('ip_address', 'like', '%' . $search . '%');
            }
        }

        // Sort by column (misalnya berdasarkan kolom yang dipilih)
        $sortBy = $request->input('sort_by', 'created_at'); // Default sort by 'created_at'
        $sortOrder = $request->input('sort_order', 'desc'); // Default order is descending (terbaru)

        // Jika sorting berdasarkan role_name, lakukan join pada tabel users, role_user, dan roles

        // Jika bukan role_name, lakukan sorting biasa
        $query->orderBy($sortBy, $sortOrder);


        // Menentukan jumlah entri per halaman (pagination)
        $entriesPerPage = $request->input('entries_per_page', 10); // Default 10 entri per halaman
        $logs = $query->paginate($entriesPerPage); // Menampilkan data log dengan pagination

        return view('pages.logs.index', compact('logs'));
    }
}
