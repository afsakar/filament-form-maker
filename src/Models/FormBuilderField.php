<?php

namespace Afsakar\FormMaker\Models;

use Afsakar\FormMaker\Enums\FieldTypes;
use Afsakar\FormMaker\Models\Scopes\OrderScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class FormBuilderField extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_builder_section_id',
        'name',
        'type',
        'options',
        'order_column',
    ];

    protected $casts = [
        'options' => 'array',
        'type' => FieldTypes::class,
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

    public function buildSortQuery(): Builder
    {
        return static::query()->where('form_builder_section_id', $this->form_builder_section_id); // @phpstan-ignore-line
    }
}
