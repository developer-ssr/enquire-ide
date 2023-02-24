<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'test_id' => 'boolean',
        'part' => 'json',
        'links_list' => 'json',
        'links_status' => 'json',
        'url_data' => 'json',
        'participant_data' => 'json',
        'recruiter_data' => 'json',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_code', 'code');
    }

    public static function getRecord($survey, $participant_id, $force = false): array
    {
        $status_code = 'passed';//= record is null for entry checker
        $record = Record::where([
            'participant_id' => $participant_id,
            'survey_code' => $survey->code,
            'test_id' => 0
        ])->first();
        if ($force == false) {
            if ($record <> null) {
                if ($survey->config['timeLimit']['qualityControl']) {
                    $diff_in_minutes = $record->created_at->diffInMinutes(now());
                    if ($diff_in_minutes > $survey->config['timeLimit']['duration']) {
                        $status_code = 'record_time_limit';
                    } else {
                        $status_code = 'record_exist';
                    }
                }else {
                    $status_code = 'record_exist';
                }
            }
        }
        return ['record' => $record, 'status_code' => $status_code];
    }
}
