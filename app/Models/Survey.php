<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Survey extends Model
{
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'config' => 'array'
    ];

    protected $appends = [
        'completes'
    ];

    protected static function booted()
    {
        static::updated(function($survey) {
            $surveys = DB::table('surveys')->where('team_id', auth()->user()->currentTeam->id)->select('uuid', 'name')->get()->toArray();
            Cache::put(auth()->user()->currentTeam->id, $surveys);
        });
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function routes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Route::class)->orderBy('part');
    }

    public function groups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function merge_groups(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MergeGroup::class);
    }

    public function panels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Panel::class);
    }

    public function scripts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Script::class);
    }

    public function filters()
    {
        return $this->hasMany(Filter::class, 'survey_id', 'id');
    }

    public function records(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Record::class, 'survey_code', 'code')->where([
            'test_id' => 0,
            'status' => 'cp'
        ]);
    }

    public function setup()
    {
        return $this->hasOne(Setup::class, 'survey_id', 'id');
    }

    public function analysis()
    {
        return $this->hasMany(Analysis::class, 'survey_id', 'id');
    }

    public static function getSurvey($survey_code): array
    {
        $survey = Survey::where('code', $survey_code)->first();
        $status_code = 'passed';
        if ($survey == null) {
            $status_code = 'survey_null';
        }else {
            if (Carbon::parse($survey->config['endDate'])->lte(Carbon::parse(now()))) {
                $status_code = 'survey_end_date';
            }elseif($survey->status == 'pause') {
                $status_code = 'survey_pause';
            }elseif ($survey->status <> 'live') {
                $status_code = 'survey_not_live';
            }
        }
        return ['survey' => $survey, 'status_code' => $status_code];
    }

    public function getCompletesAttribute()
    {
        return getCachedCompletesCount($this) ?? '-';
    }

    public function ownsSurvey($user)
    {
        return $user->id === $this->user_id;
    }

    public function members()
    {
        return $this->hasMany(SurveyUser::class, 'survey_id', 'id');
    }
}
