<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ip extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'audio_path',
        'vtt_path'
    ];
}
