<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organization extends Model
{
    protected $fillable = [
        'region_id', // Foreign key linking to the regions table
        'name',      // The name of the organization
        'acronym',   // Optional: e.g., "DSWD", "DA"
        'org_type',  // Optional: e.g., "LGU", "NGO", "Private"
        'is_active', // To toggle visibility without deleting
    ];
    
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

 
}