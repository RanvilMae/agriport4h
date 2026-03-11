<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LsaLevel extends Model
{
    public function members()
    {
        return $this->hasMany(Member::class, 'lsa_level', 'name');
    }
}
