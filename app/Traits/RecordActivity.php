<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Arr;

trait RecordActivity
{
    public static $events = ['created', 'updated', 'deleted'];

    public $oldAttributes = [];

    public static function bootRecordActivity()
    {
        foreach (static::$events as $event) {
            static::$event(function($model) use($event) {
                $model->recordActivity($event, $model);
            });

            if ($event === 'updated') {
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    public function recordActivity($event, $model)
    {
        // Quando seed il DB
        if (auth()->check()) {
            Activity::create([
                'user_id' => auth()->id(),
                'subject_type' => get_class($model),
                'subject_id' => $model->id,
                'changes' => $this->activityChanges(),
                'description' => \Illuminate\Support\Str::lower(class_basename($model)) . '_' . $event
            ]);
        }
    }

    protected function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(
                    array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'
                ),
                'after' => Arr::except(
                    $this->getChanges(), 'updated_at'
                )
            ];
        }
    }

    public function subject()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
