<?php

use app\modules\developer\generators\crud\Generator;


/* @var $this yii\web\View */
/* @var $generator Generator */


echo "<?php\n";
?>
namespace app\modules\<?= $generator->module ?>\helpers;

use app\modules\<?= $generator->module ?>\Module;

/**
* Трейт для присвоения объекту свойста readonly модуля <?= $generator->module ?>
*
* Class ModuleTrait
* @package app\modules\<?= $generator->module ?>\helpers
*
* @property-read Module $module
*/
trait ModuleTrait {

    /**
    * @return null|Module
    */
    public function getModule() {

        return app()->getModule('<?= $generator->module ?>');
    }
}