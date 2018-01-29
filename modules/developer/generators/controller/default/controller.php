<?php
/**
 * This is the template for generating a controller class file.
 */

use app\modules\developer\generators\controller\Generator;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator Generator */

echo "<?php\n";
?>

namespace <?= ltrim($generator->getControllerNamespace(), '\\') ?>;

use yii\filters\AccessControl;
use <?= $generator->baseClass ?>;
<?php if  ($generator->taskClass): ?>
use <?= $generator->taskClass ?>;
<?php endif;?>

/**
* Class <?= StringHelper::basename($generator->controllerClass) ?>
* @package  <?= $generator->getControllerNamespace() ?>
*/
class <?= StringHelper::basename($generator->controllerClass) ?> extends <?= StringHelper::basename($generator->baseClass) . "\n" ?>
{

    /**
    * @inheritdoc
    * @return array
    *
<?php if  ($generator->taskClass): ?>
    */
<?php endif ?>
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
<?php if  ($generator->taskClass): ?>
                'rules' => <?= StringHelper::basename($generator->taskClass) ?>::createRulesController(),
<?php else: ?>
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [''],
                        'roles' => ['@'],
                    ],
                ],
<?php endif ?>
            ],
        ];
    }
<?php if  (!$generator->taskClass): ?>
    */
<?php endif ?>

<?php if ($generator->titleClass) :?>
    /**
    * @param $action
    * @return bool
    * @throws \yii\web\BadRequestHttpException
    */
    public function beforeAction($action)
    {
         $this->setTitle('<?= $generator->titleClass ?>');

         return parent::beforeAction($action);
    }
<?php endif ?>

<?php if  ($generator->existsActionsMethod): ?>
    /**
    * @return array
    */
    public function actions()
    {
        return [

        ];
    }
<?php endif ?>

<?php foreach ($generator->getActionIDs() as $action): ?>
    /**
    * @return string
    */
    public function action<?= Inflector::id2camel($action) ?>()
    {
        return $this->render('<?= $action ?>');
    }

<?php endforeach; ?>
}
