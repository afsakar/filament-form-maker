<?php

namespace Afsakar\FormMaker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormBuilder extends Model
{
    use HasFactory;

    protected $with = ['sections'];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'options',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(FormBuilderSection::class, 'form_builder_id');
    }
}
