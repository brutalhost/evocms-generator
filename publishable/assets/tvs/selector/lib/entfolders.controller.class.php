<?php namespace Selector;

include_once(MODX_BASE_PATH.'assets/tvs/selector/lib/controller.class.php');
class EntfoldersController extends SelectorController
{
    public function __construct($modx) {
        parent::__construct($modx);
        $this->dlParams['idType'] = 'parents';
        $this->dlParams['parents'] = config('docshaker.generator_folder_id');
        $this->dlParams['depth'] = '1';
        $this->dlParams['addWhereList'] = 'c.published = 1 AND c.template = ' . config('docshaker.entities_folder_template_id');
    }
}
