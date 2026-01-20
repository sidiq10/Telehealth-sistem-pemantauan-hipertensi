<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'sistolik',
        'diastolik',
        'denyut_nadi',
        'catatan',
        'recommendations',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'recommendations' => 'array',
        ];
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get pasien yang memiliki record ini
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // ===== ACCESSORS & MUTATORS =====

    /**
     * Kategori tekanan darah berdasarkan nilai sistolik/diastolik
     */
    public function getStatusAttribute()
    {
        if ($this->sistolik >= 160 || $this->diastolik >= 100) {
            return 'Hipertensi Stage 2';
        } elseif ($this->sistolik >= 140 || $this->diastolik >= 90) {
            return 'Hipertensi Stage 1';
        } elseif ($this->sistolik >= 120 || $this->diastolik >= 80) {
            return 'Prehipertensi';
        } else {
            return 'Normal';
        }
    }

    /**
     * Get warna status untuk badge
     */
    public function getStatusColorAttribute()
    {
        $status = $this->getStatusAttribute();
        return match ($status) {
            'Normal' => 'green',
            'Prehipertensi' => 'yellow',
            'Hipertensi Stage 1' => 'orange',
            'Hipertensi Stage 2' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get icon emoji untuk status
     */
    public function getStatusEmojiAttribute()
    {
        $status = $this->getStatusAttribute();
        return match ($status) {
            'Normal' => '🟢',
            'Prehipertensi' => '🟡',
            'Hipertensi Stage 1' => '🟠',
            'Hipertensi Stage 2' => '🔴',
            default => '⚪',
        };
    }

    /**
     * Format created_at untuk display
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    // ===== SCOPES & QUERIES =====

    /**
     * Scope untuk mendapatkan record dari pasien tertentu
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope untuk mendapatkan record dalam range tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);
    }

    /**
     * Scope untuk mendapatkan record N hari terakhir
     */
    public function scopeLastDays($query, $days = 7)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subDays($days),
            Carbon::now(),
        ]);
    }

    /**
     * Scope untuk mendapatkan record dengan status warning
     */
    public function scopeWarning($query)
    {
        return $query->where(function ($q) {
            $q->where('sistolik', '>=', 140)
              ->orWhere('diastolik', '>=', 90);
        });
    }

    // ===== HELPER METHODS =====

    /**
     * Hitung rata-rata sistolik dan diastolik
     */
    public static function getAverageForPatient($patientId, $days = 7)
    {
        return self::forPatient($patientId)
                   ->lastDays($days)
                   ->selectRaw('AVG(sistolik) as avg_sistolik, AVG(diastolik) as avg_diastolik')
                   ->first();
    }

    /**
     * Get stats (min, max, avg) untuk pasien
     */
    public static function getStatsForPatient($patientId, $days = 7)
    {
        return self::forPatient($patientId)
                   ->lastDays($days)
                   ->selectRaw('
                       MIN(sistolik) as min_sistolik,
                       MAX(sistolik) as max_sistolik,
                       AVG(sistolik) as avg_sistolik,
                       STDDEV(sistolik) as stddev_sistolik,
                       MIN(diastolik) as min_diastolik,
                       MAX(diastolik) as max_diastolik,
                       AVG(diastolik) as avg_diastolik,
                       STDDEV(diastolik) as stddev_diastolik,
                       AVG(denyut_nadi) as avg_denyut_nadi,
                       COUNT(*) as total_records
                   ')
                   ->first();
    }
}
