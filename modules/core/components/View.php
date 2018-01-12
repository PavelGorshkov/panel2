<?php
namespace app\modules\core\components;

use yii\web\View as BaseView;

/**
 * Class View
 * @package app\modules\core\components
 */
class View extends BaseView
{
    /**
     * @var string/null - Заголовок 2 уровня страницы
     */
    public $smallTitle = null;

    /**
     * Установить заголовок 2 уровня
     *
     * @param string $title
     */
    public function setSmallTitle($title) {

        $this->smallTitle = $title;
    }

    /**
     * Установить заголовок 1го уровня
     * @param $title
     */
    public function setTitle($title) {

        $this->title = $title;
    }


    /**
     * Получить заголовок 2 уровня
     *
     * @return string
     */
    public function getSmallTitle() {

        return $this->smallTitle;
    }


    /**
     * Получить заголовок 1 уровня
     *
     * @return string
     */
    public function getTitle() {

        return $this->title;
    }


    /**
     * Установить "Хлебный крошки"
     *
     * @param array $breadcrumbs
     */
    public function setBreadcrumbs(array $breadcrumbs) {

        $this->params['breadcrumbs'] = $breadcrumbs;
    }

    /**
     * @return array
     */
    public function getBreadcrumbs() {

        return empty($this->params['breadcrumbs'])
            ?[
                [
                    'label' => $this->getTitle(),
                    'url'=>['index'],
                    'encode' => false,
                ],
                [
                    'label' => $this->getSmallTitle(),
                    'encode' => false,
                ]
            ]
            :$this->params['breadcrumbs'];
    }
}