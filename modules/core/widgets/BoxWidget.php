<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 13.12.2017
 * Time: 16:02
 */

namespace app\modules\core\widgets;

use yii\helpers\Html;

class BoxWidget extends \yii\bootstrap\Widget {

    const TYPE_INFO = 'info';

    const TYPE_PRIMARY = 'primary';

    const TYPE_SUCCESS = 'success';

    const TYPE_DEFAULT = 'default';

    const TYPE_DANGER = 'danger';

    const TYPE_WARNING = 'warning';

    const COLOR_NAVY = 'navy';
    const COLOR_LIGHT_BLUE = 'light-blue';
    const COLOR_BLUE = 'blue';
    const COLOR_AQUA = 'aqua';
    const COLOR_RED = 'red';
    const COLOR_GREEN = 'green';
    const COLOR_YELLOW = 'yellow';
    const COLOR_PURPLE = 'purple';
    const COLOR_MAROON = 'maroon';
    const COLOR_TEAL = 'teal';
    const COLOR_OLIVE = 'olive';
    const COLOR_LIME = 'lime';
    const COLOR_ORANGE = 'orange';
    const COLOR_FUCHSIA = 'fuchsia';
    const COLOR_BLACK = 'black';
    const COLOR_GRAY = 'gray';

    public $type = self::TYPE_DEFAULT;


    /**
     * @var bool
     */
    public $isTile = false;

    public $isSolid = false;

    public $withBorder = false;

    public $tooltip = '';

    public $title = '';

    public $body = '';

    public $boxTools;

    public $footer = '';

    public $boxBodyClass = '';

    public $topTemplate = <<<HTML
    <div {options}>
        <div {headerOptions}><h3 class="box-title">{title}</h3>{box-tools}</div>
        <div class="box-body{box-body-class}">
HTML;

    /**
     * @var string
     */
    public $bottomTemplate = <<<HTML
</div>
<div class="box-footer {footerOptions}">{footer}</div>
</div>
HTML;


    public function init()
    {
        Html::addCssClass($this->options, 'box');

        if(!$this->isTile) {

            Html::addCssClass($this->options, 'box-' . $this->type);
        }
        else {

            Html::addCssClass($this->options, 'bg-' . $this->type);
        }

        if ($this->isSolid || $this->isTile) {
            Html::addCssClass($this->options, 'box-solid');
        }

        echo strtr(
                $this->topTemplate,
                [
                    '{options}'       => Html::renderTagAttributes($this->options),
                    '{headerOptions}' => Html::renderTagAttributes($this->prepareHeaderOptions()),
                    '{title}'         => $this->title,
                    '{box-tools}'     => $this->prepareBoxTools(),
                    '{box-body-class}'=> $this->boxBodyClass?(' '.$this->boxBodyClass):'',
                ]
            ).$this->body;
    }


    public function run()
    {
        if($this->footer){
            return strtr(
                $this->bottomTemplate,
                [
                    '{footer}' => $this->footer,
                    '{footerOptions}' => $this->isTile?'bg-'.$this->type:'',
                ]
            );
        }else {
            return '</div></div>';
        }
    }


    protected function prepareHeaderOptions()
    {
        $headerOptions = ['class' => 'box-header'];
        if ($this->withBorder) {
            Html::addCssClass($headerOptions, 'with-border');
        }
        if ($this->tooltip) {
            $headerOptions = array_merge(
                $headerOptions,
                [
                    'data-toggle'         => 'tooltip',
                    'data-original-title' => $this->tooltip,
                    'data-placement'      => $this->tooltipPlacement ?: 'bottom',
                ]
            );
        }
        return $headerOptions;
    }


    protected function prepareBoxTools()
    {
        $boxTools = '';
        if (!empty($this->boxTools)) {
            if (is_array($this->boxTools)) {
                $boxTools = ButtonGroup::widget(
                    [
                        'buttons'      => $this->boxTools,
                        'encodeLabels' => false,
                    ]
                );
            } else {
                $boxTools = $this->boxTools;
            }
        }
        return $boxTools ? Html::tag('div', $boxTools, ['class' => 'box-tools pull-right']) : '';
    }
}