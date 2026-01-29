<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ClientFollowup extends Model
{
    use LogsActivity;

    protected $fillable = [
        'client_id', 'remarks', 'response_type', 'followup_date', 'created_by'
    ];

    protected $casts = [
        'followup_date' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
