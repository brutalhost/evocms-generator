<?php

namespace EvolutionCMS\Generator\Models;

use Carbon\Carbon;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Matrix extends Model
{
    protected $table = 'generator_matrix';

    public function getFolders(string $tvList = '', string $selectFields = 'id,pagetitle')
    {
        $folders = evo()->runSnippet('DocLister', [
            'api' => 1,
            'idType' => 'documents',
            'sortType' => 'doclist',
            'selectFields' => $selectFields,
            'tvList' => $tvList,
            'documents' => $this->folders_id,
        ]);
        return json_decode($folders);
    }

    public function getTemplate() {
        return SiteTemplate::where('id', $this->site_content_template)->get();
    }
}
