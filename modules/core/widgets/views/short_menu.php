<?php
/* @var $this \app\modules\core\components\View */

use yii\helpers\Html;
use yii\helpers\Url;

if (!count($menu)) return;
?>
<h4><?=$title?></h4>
<div class="shortcuts_menu">
<?php
    foreach ($menu as $item){
         $label = '<div class="cn">'.$item['label'].'</div>';
         echo Html::a($label, Url::to($item['url']));
    }
?>
</div>
<?php
    $this->registerCss( /** @lang text */
        <<<CSS

.shortcuts_menu {
    text-align: left;
}

.shortcuts_menu a {
    min-height: 114px;
    text-align: center;
    width: 114px;
    display: inline-block;
    padding: 12px 0;
    margin: 0 5px 1em;
    vertical-align: top;
    text-decoration: none;
    background: #F3F3F3;
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    box-sizing: border-box;
    border-radius: 5px;
    position: relative;
}

.shortcuts_menu a i {
    width: 100%;
    margin-top: .25em;
    margin-bottom: .35em;
    font-size: 32px;
    color: #555;
}

.shortcuts_menu a:hover {
    background: #E8E8E8;
    background-color: #f0f0f0;
    background-repeat: repeat-x;
}

.shortcuts_menu a:hover i {
    color: #666;
}

.shortcuts_menu .shortcut-label {
    display: block;
    font-weight: 400;
    color: #666;
    margin-top: 5px;
}

.action-nav-button {
    margin-bottom: 15px;
    position: relative;
    display: inline-block;
    text-align: center;
    min-height: 114px;
    width: 114px;
    display: inline-block;
}

#page .shortcut .label i {
    margin-right: 2px;
    color: #fff;
}

a .cn {
    display: table-cell;
    vertical-align: middle;
    /* text-align: center; */
    width: 114px;
    height: 120px;
}

CSS
);