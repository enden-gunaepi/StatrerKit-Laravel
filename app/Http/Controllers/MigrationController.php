<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class MigrationController extends Controller
{
    public function runMigrations()
    {
        $logs = []; // Untuk menyimpan hasil perubahan

        // Daftar tabel dan field yang ingin ditambahkan jika belum ada
        $tables = [
            'logs' => [
                'ip_address' => ['type' => 'string', 'nullable' => true, 'after' => 'category'],

            ],
            'users' => [
                'profile_picture' => ['type' => 'string', 'nullable' => true, 'after' => 'email'],

            ],
            // Tambahkan tabel lainnya jika diperlukan
        ];


        foreach ($tables as $table => $columns) {
            if (Schema::hasTable($table)) {
                $addedColumns = []; // Menyimpan kolom yang berhasil ditambahkan

                Schema::table($table, function (Blueprint $table) use ($columns, &$addedColumns) {
                    foreach ($columns as $column => $attributes) {
                        if (!Schema::hasColumn($table->getTable(), $column)) {
                            if ($attributes['type'] === 'string') {
                                $table->string($column)->nullable()->after($attributes['after']);
                            } elseif ($attributes['type'] === 'text') {
                                $table->text($column)->nullable()->after($attributes['after']);
                            } elseif ($attributes['type'] === 'integer') {
                                $table->integer($column)->nullable()->after($attributes['after']);
                            }
                            $addedColumns[] = $column; // Simpan kolom yang berhasil ditambahkan
                        }
                    }
                });

                // Jika ada kolom yang ditambahkan, simpan ke logs
                if (!empty($addedColumns)) {
                    $logs[$table] = $addedColumns;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Migration telah dijalankan dan field baru telah ditambahkan jika belum ada.',
            'changes' => $logs ?: 'Tidak ada perubahan, semua field sudah ada.'
        ]);
    }
}
