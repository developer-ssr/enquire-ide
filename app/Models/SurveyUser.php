<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyUser extends Model
{
    use HasFactory;

    protected $table = 'survey_user';

    protected $guarded = [];

    public function surveys()
    {
        return $this->hasOne(Survey::class, 'survey_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
