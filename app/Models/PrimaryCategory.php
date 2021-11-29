<?php

namespace App\Models;

use App\Models\SecondaryCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryCategory extends Model
{
    use HasFactory;

    public function secondary()
    {
        return ($this->hasMany(SecondaryCategory::class));
    }
}
