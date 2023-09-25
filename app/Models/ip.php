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
        'vtt_path',
        'transcription_status',
    ];

    public function getCreatedAtAttribute($value) {
        return $this->attributes['created_at'] = date('d,F-Y', strtotime($value));
    }

    public function transcriber()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
