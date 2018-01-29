<?php
/**
 * This is the template for generating a controller class file.
 */

use app\modules\developer\generators\console\Generator;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator Generator */

echo "<?php\n";
?>

namespace <?= ltrim($generator->getControllerNamespace(), '\\') ?>;


use <?= $generator->baseClass ?>;
<?php if  ($generator->hasCron): ?>
use app\modules\core\interfaces\RegisterCommandInterface;
<?php endif;?>

/**
* Class <?= StringHelper::basename($generator->controllerClass) ?>
* @package  <?= $generator->getControllerNamespace() ?>
*/
class <?= StringHelper::basename($generator->controllerClass) ?> extends <?= StringHelper::basename($generator->baseClass) ?><?= $generator->hasCron?' implements RegisterCommandInterface':'' ?><?= "\n" ?>
{

<?php if  ($generator->hasCron): ?>
    /**
    * Получение списка команд в контроллере, которые будут участвовать в заданиях cron-модуля
    * @return array
    */
    public static function getList()
    {
        return [
<?php foreach ($generator->getActionIDs() as $action): ?>
            '<?= $action ?>' => '<?= Inflector::camel2words($action)?>',
<?php endforeach ?>
        ];
    }
<?php endif;?>

<?php foreach ($generator->getActionIDs() as $action): ?>
    /**
    * @return string
    */
    public function action<?= Inflector::id2camel($action) ?>()
    {
        //TODO create code for action <?= Inflector::camel2words($action) ?>
    }

<?php endforeach; ?>
}
