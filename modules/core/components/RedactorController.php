<?php
namespace app\modules\core\components;

use app\modules\core\helpers\RouterUrlHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\HttpException;

/**
 * Контроллер для вывода данных Редактору прилодения
 *
 * Class RedactorController
 * @package app\modules\core\components
 */
class RedactorController extends WebController {

    protected $actionMenu = null;

    public $layout = '@app/modules/core/views/layouts/redactor_menu';

    public function beforeAction($action) {

        parent::beforeAction($action);

        if (empty($this->actionMenu) || !is_array($this->actionMenu)) {

            throw new HttpException('500', 'In class '.__CLASS__.' not found property actionMenu');
        }

        $this->view->params['actionMenu'] = $this->getActionsMenu();
        $this->setTitle('Управление модулями');

        if (isset($this->actionMenu[$this->action->id]))
            $this->setSmallTitle($this->actionMenu[$this->action->id]);

        return true;
    }


    public function getActionsMenu() {

        $menu = [];

        foreach ($this->actionMenu as $url => $label) {

            $menu[] = [
                'label'=>$label,
                'url'=>[$url],
                'active'=>RouterUrlHelper::isActiveRoute($url),
                'visible'=>app()->moduleManager->can($this->module->id, RouterUrlHelper::to($url)),
            ];
        }

        return $menu;
    }
}