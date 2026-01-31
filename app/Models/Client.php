<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity;

    protected $fillable = [
        'company_name', 'contact_person', 'phone', 'email', 'address', 'created_by', 'is_archived', 'status_color'
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function followups()
    {
        return $this->hasMany(ClientFollowup::class);
    }

    /**
     * Scope for active (non-archived) clients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope for archived clients.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
}
