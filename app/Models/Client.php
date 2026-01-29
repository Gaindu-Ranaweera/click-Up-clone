<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity;

    protected $fillable = [
        'company_name', 'contact_person', 'phone', 'email', 'address', 'created_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function followups()
    {
        return $this->hasMany(ClientFollowup::class);
    }
}
