<?php
    /* @var View $this */
    /* @var FormModel $model */
    /* @var array $data */
    /* @var string $colStyle */
    /* @var string $columnCount */
    /* @var string $attribute */

    use app\modules\core\components\FormModel;
    use app\modules\core\components\View;
    use app\modules\cron\helpers\CronHelper;
    use yii\helpers\Html;

    $id = $model->formName().'-'.$attribute;
    $name = $model->formName().'['.$attribute.']';
    $id_check_all = 'js_check_'.$id;
    $id_delete_all = 'js_delete_'.$id;
    $elementValue = $model->$attribute ? $model->$attribute : [];
?>

<div class="panel panel-primary">
    <div class="panel-heading"><?=CronHelper::getTimeParamTitle($attribute)?></div>
    <div class="panel-body">

        <input id="yt<?=$id?>" value="" name="<?=$name?>" type="hidden">

        <span id="<?=$id?>">
            <?php foreach ($data as $value): ?>
                <div class="row" style="word-break:break-all;">
                    <div class="col-sm-12">
                        <?php foreach ($value as $key => $title): ?>

                            <?php $item_id = $id.'_'.$key; ?>

                            <div class="<?=$colStyle?> col-xs-6">
                                <input id="<?=$item_id?>" value="<?=$key?>" <?=isset(array_flip($elementValue)[$key])?"checked=\"checked\"":""?> name="<?=$name?>[]" type="checkbox">
                                <label for="<?=$item_id?>"><?=$title?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if($data): ?>
                <div class="text-center">
                    <?= Html::a('Отметить всё', 'javascript:void(0)', ['id' => $id_check_all, 'class'=>'btn btn-sm btn-primary', 'style'=>'margin-top: 5px;']) ?>
                    <?= Html::a('Снять отметки', 'javascript:void(0)', ['id' => $id_delete_all, 'class'=>'btn btn-sm btn-default', 'style'=>'margin-top: 5px;']) ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Нет данных для отображения!</div>
            <?php endif; ?>

        </span>
    </div>
</div>


<?php

$this->registerJs(/** @lang text */
<<<JS
!function ($) {
    $(function() { 
               
        $('#$id_check_all').click( function() {               
            $('#$id input').each(function(){      
                $(this).prop('checked', true);
            });        
        }); 
        
        $('#$id_delete_all').click( function() {      
            $('#$id input').each(function(){      
                $(this).prop('checked', false);
            }); 
        }); 
            
    })
}(window.jQuery)
JS
    , $this::POS_END);