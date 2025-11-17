<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get pengirim pesan
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get penerima pesan
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ===== ACCESSORS =====

    /**
     * Get formatted created_at
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    /**
     * Get waktu baca yang friendly
     */
    public function getReadTimeAttribute()
    {
        return $this->read_at ? $this->read_at->diffForHumans() : null;
    }

    // ===== SCOPES =====

    /**
     * Scope unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope between two users (conversasi)
     */
    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($q2) use ($userId1, $userId2) {
                $q2->where('sender_id', $userId1)->where('receiver_id', $userId2);
            })->orWhere(function ($q2) use ($userId1, $userId2) {
                $q2->where('sender_id', $userId2)->where('receiver_id', $userId1);
            });
        });
    }

    /**
     * Scope for receiver
     */
    public function scopeForReceiver($query, $receiverId)
    {
        return $query->where('receiver_id', $receiverId);
    }

    /**
     * Scope for sender
     */
    public function scopeForSender($query, $senderId)
    {
        return $query->where('sender_id', $senderId);
    }

    // ===== HELPER METHODS =====

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);
    }

    /**
     * Get conversation between two users
     */
    public static function getConversation($userId1, $userId2)
    {
        return self::betweenUsers($userId1, $userId2)
                   ->latest('created_at')
                   ->get();
    }
}
