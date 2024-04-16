<?php

namespace EvolutionCMS\Generator\Models;

use EvolutionCMS\Models\SiteContent;
use Illuminate\Database\Eloquent\Builder;

class EntitieFolder extends SiteContent
{
    protected $table = 'site_content';

    protected static function booted()
    {

        static::addGlobalScope('custom_template', function (Builder $builder) {
            $builder->where('template', 3);
        });
    }

    public function matrices()
    {
        return $this->belongsToMany(Matrix::class, 'generator_matrix_entities_folder', 'entities_folder_id', 'matrix_id');
    }
}