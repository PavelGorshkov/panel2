<?php
namespace app\modules\core\components;

use app\modules\core\helpers\RouterUrlHelper;
use yii\base\InvalidConfigException;
use yii\web\HttpException;

/**
 * Контроллер для вывода данных Редактору прилодения
 *
 * Class RedactorController
 * @package app\modules\core\components
 */
class RedactorController extends WebController
{
    protected $actionMenu = null;

    public $layout = '@app/modules/core/views/layouts/redactor_menu';

    protected $title = null;

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     *
     * @throws \yii\web\BadRequestHttpException
     * @throws InvalidConfigException
     */
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (empty($this->actionMenu) || !is_array($this->actionMenu)) {

            throw new HttpException('500', 'In class ' . __CLASS__ . ' not found property actionMenu');
        }

        $this->view->params['actionMenu'] = $this->getActionsMenu();

        if ($this->title !== null) $this->setTitle($this->title);

        if (isset($this->actionMenu[$this->action->id]))
            $this->setSmallTitle($this->actionMenu[$this->action->id]);

        if (isset($this->actionMenu['@' . $this->action->id]))
            $this->setSmallTitle($this->actionMenu['@' . $this->action->id]);

        return true;
    }


    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getActionsMenu()
    {
        $menu = [];

        foreach ($this->actionMenu as $url => $label) {

            if ($url !== ltrim($url, '@')) {

                $url = ltrim($url, '@');
                $access = false;
            } else
                $access = true;

            $menu[] = [
                'label' => $label,
                'url' => [$url],
                'active' => RouterUrlHelper::isActiveRoute($url),
                'visible' => $access ? app()->moduleManager->can($this->module->id, RouterUrlHelper::to($url)) : true,
            ];
        }

        return $menu;
    }
}