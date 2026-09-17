<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatHandover extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_handovers';
    protected $fillable = [
        'id_sertifikat',
        'from_user_id',
        'to_user_id',
        'status',
        'notes',
        'accepted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public const STATUSES = ['pending', 'accepted', 'rejected'];

    /**
     * Relation to certificate
     */
    public function sertifikat()
    {
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }

    /**
     * Relation to from user (Sekretariat)
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    /**
     * Relation to to user (Mentor)
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    /**
     * Mark handover as accepted
     */
    public function accept()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Update certificate status
        $this->sertifikat->update([
            'sertifikat_status' => 'handed_over',
            'handed_to_mentor_id' => $this->to_user_id,
            'handed_over_at' => now(),
        ]);
    }

    /**
     * Mark handover as rejected
     */
    public function reject()
    {
        $this->update([
            'status' => 'rejected',
        ]);

        // Revert certificate status to ready for handover
        $this->sertifikat->update([
            'sertifikat_status' => 'ready_for_handover',
        ]);
    }

    /**
     * Scope to pending handovers
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to handovers by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('from_user_id', $userId)->orWhere('to_user_id', $userId);
    }
}
