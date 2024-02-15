<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Answer;

class Media extends Model
{
    protected $fillable = ['file_name', 'file_type', 'question_id', 'answer_id'];

    public function questionId()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function answerId()
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}

