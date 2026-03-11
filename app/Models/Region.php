<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    // ... existing code ...

    /**
     * Get the organizations for the region.
     */
    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }

    /**
     * Get the provinces for the region.
     */
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members(): HasMany
    {
        // Assuming your members table has a 'region_id' column
        return $this->hasMany(Member::class, 'region_id');
    }

    public function organization()
    {
        return $this->hasMany(Organization::class);
    }
}