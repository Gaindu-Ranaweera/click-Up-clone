<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureRequest extends Model
{
    protected $fillable = [
        'user_id', 'feature_id', 'status', 'reason', 'requested_permissions'
    ];

    protected $casts = [
        'requested_permissions' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
