<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'description', 'user_agent', 'category', 'external_id', 'ip_address', 'timezone', 'created_at'
    ];

    // Relasi dengan User (optional, jika user_id ada)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->ip_address = request()->ip(); // Ambil IP address otomatis

            // Mengambil timezone dari settingan sistem atau default 'Asia/Jakarta'
            $timezone = get_setting('timezone', 'Asia/Jakarta'); // Mengambil timezone dari pengaturan
            // $timezone = config('app.timezone', 'Asia/Jakarta');

            // Menyesuaikan waktu dengan timezone yang dipilih
            $log->created_at = Carbon::now($timezone); // Waktu pembuatan log berdasarkan timezone
        });
    }
}
