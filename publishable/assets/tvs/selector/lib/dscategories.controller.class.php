<?php use Selector\SelectorController;

include_once(MODX_BASE_PATH.'assets/tvs/selector/lib/controller.class.php');
class DscategoriesController extends SelectorController
{
    public function __construct($modx) {
        parent::__construct($modx);
        $this->dlParams['parents'] = config('docshaker.categories_folder_id');
        $this->dlParams['showParent'] = 0;
        $this->dlParams['addWhereList'] = 'c.published = 1';
    }
}
