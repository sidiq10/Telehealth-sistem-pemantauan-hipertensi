<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'birthdate',
        'address',
        'points',
        'streak_days',
        'last_data_entry_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'last_data_entry_date' => 'date',
        ];
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get health records untuk pasien
     */
    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'patient_id');
    }

    /**
     * Get dokter yang menangani pasien (relasi many-to-many)
     */
    public function doctors()
    {
        return $this->belongsToMany(User::class, 'doctor_patient', 'patient_id', 'doctor_id')
                    ->where('role', 'dokter');
    }

    /**
     * Get pasien yang ditangani dokter (relasi many-to-many)
     */
    public function patients()
    {
        return $this->belongsToMany(User::class, 'doctor_patient', 'doctor_id', 'patient_id')
                    ->where('role', 'pasien');
    }

    /**
     * Get feedback yang dikirim user
     */
    public function sentFeedbacks()
    {
        return $this->hasMany(Feedback::class, 'sender_id');
    }

    /**
     * Get feedback yang diterima user
     */
    public function receivedFeedbacks()
    {
        return $this->hasMany(Feedback::class, 'receiver_id');
    }

    /**
     * Get unread feedback count
     */
    public function unreadFeedbackCount()
    {
        return $this->receivedFeedbacks()->where('is_read', false)->count();
    }

    /**
     * Get badges earned by user
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }
}
