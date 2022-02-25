<?php

namespace App\Models;

use App\Models\PrimaryCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryCategory extends Model
{
    use HasFactory;

    public function primary()
    {
        return ($this->belongsTo(PrimaryCategory::class));
    }
}
