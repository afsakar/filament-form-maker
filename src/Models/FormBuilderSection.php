<?php

namespace Afsakar\FormMaker\Models;

use Afsakar\FormMaker\Models\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class FormBuilderSection extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $with = ['fields'];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_builder_id',
        'title',
        'columns',
        'options',
        'order_column',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new OrderScope('order_column', 'asc'));
    }

    public function builder(): BelongsTo
    {
        return $this->belongsTo(FormBuilder::class, 'form_builder_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormBuilderField::class, 'form_builder_section_id');
    }

    public function buildSortQuery(): Builder
    {
        return static::query()->where('form_builder_id', $this->form_builder_id); // @phpstan-ignore-line
    }
}
