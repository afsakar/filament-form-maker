<?php

namespace Afsakar\FormMaker\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormBuilderCollection extends Model
{
    use HasFactory;

    protected $casts = [
        'values' => 'collection',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'types',
        'values',
        'model',
    ];
}
