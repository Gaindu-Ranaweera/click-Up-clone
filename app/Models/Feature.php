<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['name', 'key', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_features')
                    ->withPivot('is_enabled')
                    ->withTimestamps();
    }
}
