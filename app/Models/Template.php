<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'editable_regions'];
    protected $casts = ['editable_regions' => 'array'];
}
