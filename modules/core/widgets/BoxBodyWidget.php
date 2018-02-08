<?php


namespace app\modules\core\widgets;

/**
 * Class BoxBody
 * @package app\modules\core\widgets
 */
class BoxBodyWidget extends BoxWidget
{
    public $topTemplate = /** @lang text */
        <<<HTML
    <div {options}>
        <div class="box-body{box-body-class}">
HTML;

    public $isSolid = true;

}