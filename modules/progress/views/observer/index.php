<?php

use app\modules\progress\models\Observer;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $min integer */
/* @var $max integer */
/* @var $year integer */
/* @var $model Observer */

?>

<!-- Построение панели навигации -->
<?php if($max): ?>
    <?=$this->render('_navigation', ['min' => $min, 'max' => $max, 'year' => $year, 'model' => $model])?>
<?php else: ?>
    <div class="alert alert-warning">Данные отсутствуют!</div>
<?php endif; ?>

<div id="unit_statistic"></div>
<div id="group_statistic"></div>


<?php
$form_menu_id = $model->getFormMenuId();
$unit_list_group_id = 'unit_list_group_id';

$url_unit = Url::to(['unit']);
$url_group = Url::to(['group']);

$this->registerJs( /** @lang text */
<<<JS

    !function ($) {
        $(function() {
            
            //Запрос на получение данных по факультетам
            function formMenuRequest(element){
                var year = element.attr('data-year'),
                    form = element.attr('data-form');  
                
                $.ajax({url : "{$url_unit}", type : "POST", data : {'year' : year, 'form' : form}})
                 .done(function(data){ 
                     $('#group_statistic').empty();
                     $('#unit_statistic').html(data);
                 });                
            }
        
            
            //Первоначальная загрузка данных
            if($("#{$form_menu_id} .active a").length) {
                formMenuRequest($("#{$form_menu_id} .active a"));
            }            
            
            //Нажатие на меню выбора формы обучения
            $('#{$form_menu_id} a').click(function(){
                formMenuRequest($(this));
            });

            
            //Установка активности на кнопку меню выбора формы обучения
            $('#{$form_menu_id} li').click(function(){
                $('#{$form_menu_id} li').each(function(){
                    $(this).attr('class', '');                     
                });                         
                $(this).attr('class', 'active');            
            });
            
            
            //Запрос по нажатию на факультет
            $('#unit_statistic').on('click', '#{$unit_list_group_id} a', function(){
                $('#unit_statistic #{$unit_list_group_id} a').each(function(){
                    $(this).removeClass('active');                     
                });                         
                $(this).addClass('active');
                
                var year = $(this).attr('data-year'),
                    form = $(this).attr('data-form'),
                    unit = $(this).attr('data-unit');
                
                $.ajax({url : "{$url_group}", type : "POST", data : {'year' : year, 'form' : form, 'unit' : unit}})
                 .done(function(data){ 
                     $('#group_statistic').html(data);
                 });                
            });
        })
    }(window.jQuery)
JS
        ,View::POS_LOAD);
