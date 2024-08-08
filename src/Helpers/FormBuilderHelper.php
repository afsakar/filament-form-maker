<?php

namespace Afsakar\FormMaker\Helpers;

use Afsakar\FormMaker\Models\Traits\InteractsWithFormBuilderCollection;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class FormBuilderHelper
{
    public static function getModels($directory): array
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $models = [];

        foreach ($files as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $path = $file->getRealPath();
            $relativePath = Str::after($path, realpath(app_path('Models')) . DIRECTORY_SEPARATOR);
            $class = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

            if (class_exists($class)) {
                $models[] = $class;
            }
        }

        return $models;
    }

    public static function hasTrait($class, $trait): bool
    {
        $reflector = new ReflectionClass($class);

        return in_array($trait, $reflector->getTraitNames(), true);
    }

    public static function getAllResources(): array
    {
        $modelsDirectory = app_path('Models');
        $allModels = self::getModels($modelsDirectory);
        $modelsWithTrait = [];

        foreach ($allModels as $model) {
            if (self::hasTrait($model, InteractsWithFormBuilderCollection::class)) {
                $modelLabel = new ($model);

                $className = $modelLabel::getClassName();

                $modelsWithTrait[$model] = $className;
            }
        }

        return $modelsWithTrait;
    }
}
