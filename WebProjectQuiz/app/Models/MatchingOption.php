<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingOption extends Model
{
    protected $table = 'matching_options';

    protected $fillable = [
        'question_id',
        'option_text',
        'image', // Yeni eklendi
        'pair_order',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
