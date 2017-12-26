<?php
namespace app\modules\core\widgets;

use app\modules\user\helpers\UserSettings;
use yii\bootstrap\ButtonGroup;
use yii\grid\GridView;
use yii\web\View;


class CustomGridView extends GridView {

    /**
     *  constant of headline positions:
     * @uses renderHeadline
     * @var string
     **/
    const HP_LEFT = 'left';
    /**
     *
     */
    const HP_RIGHT = 'right';

    public $pageSizes = [5, 10, 15, 20, 50, 100];

    public $pageSizeVarName = 'pageSize';

    protected $_pageSizesEnabled = false;

    public $headlinePosition = self::HP_RIGHT;

    public $layout = "{summary}\n{items}\n{pager}<div class='pull-right'>{headline}</div>";


    /**
     * @var string
     */
    public $pagerCssClass = 'pager-container';

    /**
     * @var bool
     */
    public $actionsButtons = null;

    /**
     * @var bool
     */
    public $enableHistory = true;


    public function renderBulkActions()
    {
        echo '<tr><td colspan="'.count($this->columns).'" class="grid-toolbar">';

        if (!empty($this->actionsButtons)) {

            if (is_array($this->actionsButtons)) {

                foreach ($this->actionsButtons as $button) {
                    echo $button;
                }

            } else {

                if (is_string($this->actionsButtons)) {

                    echo $this->actionsButtons;
                }
            }
        }
        echo '</td></tr>';
    }


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {

        $this->headlinePosition = empty($this->headlinePosition) ? self::HP_RIGHT : $this->headlinePosition;

        $this->initPageSizes();

        parent::init();
    }


    protected function _getPageSize($currentPageSize = 0) {

        $pageSize = UserSettings::model()->pageSize;

        if ($pageSize === null) {

            UserSettings::model()->pageSize = $currentPageSize;
            $pageSize = $currentPageSize;
        }

        return $pageSize;
    }


    protected function _updatePageSize($pageSize)
    {
        if ($pageSize !== UserSettings::model()->pageSize) {

            UserSettings::model()->pageSize = $pageSize;
        }
    }


    protected function initPageSizes()
    {
        $pagination = $this->dataProvider->getPagination();

        $pageSize = $this->_getPageSize(isset($pagination->pageSize)?$pagination->pageSize:0);

        if (
            strpos($this->layout, '{headline}') === false
            || $pagination === false
        ) {
            $this->_pageSizesEnabled = false;
        } else {
            $this->_pageSizesEnabled = true;

            // Web-user specifies desired page size.
            if (($pageSizeFromRequest = app()->request->get($this->pageSizeVarName)) !== null) {

                $pageSizeFromRequest = (int)$pageSizeFromRequest;
                // Check whether given page size is valid or use default value
                if (in_array($pageSizeFromRequest, $this->pageSizes)) {
                    $pagination->pageSize = $pageSizeFromRequest;
                    $this->_updatePageSize($pageSizeFromRequest);
                }
            } // Check for value at session or use default value
            else {

                $pagination->pageSize = $pageSize;
            }
        }
    }


    /**
     * @throws \Exception
     */
    public function renderHeadline() {

        if (!$this->_pageSizesEnabled || $this->dataProvider->getTotalCount() < 5) {
            return;
        }

        $buttons = [];

        $currentPageSize = $this->dataProvider->getPagination()->pageSize;

        foreach ($this->pageSizes as $pageSize) {

            $buttons[] = [
                'label' => $pageSize,
                'active' => $pageSize == $currentPageSize,
                'htmlOptions' => [
                    'class' => 'pageSize btn btn-sm',
                    'rel' => $pageSize,
                ],
                'url' => '#',
            ];
        }

        echo 'Выводить по';

        echo ButtonGroup::widget(['buttons' => $buttons,]);

        $csrfTokenName = app()->request->csrfParam;
        $csrfToken = app()->request->getCsrfToken();

        $csrf = app()->request->enableCsrfValidation === false
            ? ""
            : ", '$csrfTokenName':'{$csrfToken}'";

        $this->view->registerJs( /** @lang text */
            <<<JS
            (function () {
    $('body').on('click', '#{$this->getId()} .pageSize', function (event) {
        event.preventDefault();
        $('#{$this->getId()}').yiiGridView('update',{
            url: window.location.href,
            data: {
                '{$this->pageSizeVarName}': $(this).attr('rel')$csrf
            }
        });
    });
})();
JS
, View::POS_END);

    }


    /**
     * @param $name
     *
     * @return void
     * @throws \Exception
     */
    public function renderSection($name) {

        switch ($name) {

            case '{{headline}}':
                echo $this->renderHeadline();
                break;

            default:
                echo parent::renderSection($name);
                break;
        }
    }
}