<?php use Selector\SelectorController;

include_once(MODX_BASE_PATH.'assets/tvs/selector/lib/controller.class.php');
class DsentitiesController extends SelectorController
{
    public function __construct($modx) {
        parent::__construct($modx);
        $this->dlParams['showParent'] = 0;
        $this->dlParams['depth'] = 2;
        $this->dlParams['parents'] = config('docshaker.generator_folder_id');
        $this->dlParams['addWhereList'] = 'c.template = '.config('docshaker.entities_template_id');
    }
}
