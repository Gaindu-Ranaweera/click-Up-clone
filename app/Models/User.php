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
        'role_id',
        'phone',
        'address',
        'profile_picture',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'user_features')
                    ->withPivot('is_enabled', 'can_edit', 'can_delete')
                    ->withTimestamps();
    }

    public function featureRequests()
    {
        return $this->hasMany(FeatureRequest::class);
    }

    /**
     * Check if user has a specific feature enabled.
     */
    public function hasFeature(string $key): bool
    {
        return $this->features()
                    ->where('key', $key)
                    ->where('user_features.is_enabled', true)
                    ->exists();
    }

    /**
     * Check if user has granular permission for a feature.
     */
    public function hasPermission(string $featureKey, string $permission): bool
    {
        if ($this->hasRole('super_admin')) {
            return true;
        }

        $feature = $this->features()->where('key', $featureKey)->first();
        
        if (!$feature || !$feature->pivot->is_enabled) {
            return false;
        }

        // permission should be 'can_edit' or 'can_delete'
        $pivotField = 'can_' . str_replace('can_', '', $permission);
        return (bool) $feature->pivot->$pivotField;
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
}
