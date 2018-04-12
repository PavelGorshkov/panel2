<?php

namespace app\modules\core\widgets;

use app\modules\core\helpers\RouterUrlHelper;
use kartik\grid\ActionColumn;
use yii\helpers\Html;

/**
 * Class CustomActionColumn
 * @package app\modules\core\widgets
 */
class CustomActionColumn extends ActionColumn
{
    public $header = '&nbsp;';

    public $template = '{view}&nbsp;{update}&nbsp;{delete}';

    public $width = null;

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        if (user()->can(RouterUrlHelper::to('view'))) {

            $this->initDefaultButton('view', 'eye');
        }

        if (user()->can(RouterUrlHelper::to('update'))) {

            $this->initDefaultButton('update', 'pencil', ['class' => 'btn btn-warning btn-xs']);
        }

        if (user()->can(RouterUrlHelper::to('delete'))) {

            $this->initDefaultButton('delete', 'trash', [
                'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                'data-method' => 'post',
                'class' => 'btn btn-danger btn-xs'
            ]);
        }
    }


    /**
     * Initializes the default button rendering callback for single button.
     * @param string $name Button name as it's written in template
     * @param string $iconName The part of Bootstrap glyphicon class that makes it unique
     * @param array $additionalOptions Array of additional options
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url) use ($name, $iconName, $additionalOptions) {//$model, $key
                switch ($name) {
                    case 'view':
                        $title = \Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = \Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = \Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);

                $icon = Html::tag('i', '', ['class' => "fa fa-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}