<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'sex',
        'civil_status',
        'dob',
        'contact_no',
        'email',
        'region_id',
        'province_id',
        'city_municipality',
        'district',
        'barangay',
        'zip_code',
        'member_type',
        'occupation',
        'organization_id',
        'specialization',
        'hvcdp_category',
        'crops',
        'services',
        'internship',
        'scholarship',
        'lsa_level',
        'lsa_type',
        'training_course',
        'member_id',
        'uid'
    ];

    /**
     * Note: You don't need both $fillable and $guarded = []. 
     * Since you've listed $fillable, you can remove $guarded or leave it as is.
     */

    protected $casts = [
        'dob' => 'date',
        'services' => 'array',
        'crops' => 'array', // Synced with your form field name
    ];

    /**
     * Virtual Age Attribute
     * Usage: $member->age
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->dob ? Carbon::parse($this->dob)->age : null,
        );
    }

    /**
     * Full Name Attribute
     * Usage: $member->full_name
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}"),
        );
    }

    /**
     * Relationships
     */

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * If lsa_level is a string in your 'members' table (from the form),
     * this relationship will fail unless lsa_level_id exists.
     * If it's just a string, remove this and use $member->lsa_level.
     */
    public function lsaLevelRelation(): BelongsTo
    {
        return $this->belongsTo(LsaLevel::class, 'lsa_level_id');
    }

    /**
     * Constants/Helpers
     */
    public static function suffixes()
    {
        return ['Jr.', 'Sr.', 'II', 'III', 'IV', 'V'];
    }

    public static function generateUid($regionCode)
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;

        // Format: 4H - REGION - YEAR - 000 - 00001
        return sprintf(
            "4H-%s-%s-000-%05d",
            $regionCode,
            $year,
            $count
        );
    }

    public function lsaLevel()
    {
        return $this->belongsTo(LsaLevel::class, 'lsa_level', 'name');
    }
}