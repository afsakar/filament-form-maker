<?php

namespace Afsakar\FormMaker\Models;

use Afsakar\FormMaker\Enums\FormStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FormBuilderData extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locale',
        'fields',
        'status',
        'ip',
        'user_agent',
        'url',
    ];

    protected static function booted()
    {
        self::creating(function ($model) {
            $model->ip = request()->ip();
            $model->user_agent = request()->userAgent();
        });
    }

    public function scopeOpen(Builder $builder)
    {
        return $builder->whereStatus(FormStatus::OPEN); // @phpstan-ignore-line
    }

    public function scopeClosed(Builder $builder)
    {
        return $builder->whereStatus(FormStatus::CLOSED); // @phpstan-ignore-line
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => FormStatus::class,
            'fields' => 'array',
        ];
    }
}
