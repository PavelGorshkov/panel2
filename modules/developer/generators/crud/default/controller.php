<?php

use app\modules\developer\generators\crud\Generator;
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

$formModelClass = StringHelper::basename($generator->formModelClass);
if ($modelClass === $formModelClass) {
    $formModelAlias = $formModelClass . 'Form';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$urlParams = $generator->generateUrlParams();

$taskClass = StringHelper::basename($generator->taskClass);

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use app\modules\core\components\actions\GridViewAction;
use app\modules\core\components\actions\SaveModelAction;
use yii\filters\AccessControl;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\filters\VerbFilter;
use <?= ltrim($generator->modelClass, '\\') ?>;
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
use <?= ltrim($generator->formModelClass, '\\') . (isset($formModelAlias) ? " as $formModelClass" : "") ?>;
use <?= ltrim($generator->taskClass, '\\') ?>;
use yii\web\NotFoundHttpException;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 *
 * Class ManagerController
 * @package app\modules\user\controllers
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => <?= $taskClass ?>::createRulesController(),
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     *
    public function beforeAction($action)
    {
        $this->setTitle('Установите заголовок');

        return parent::beforeAction($action);
    }
    */


    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' =>[
                'class'=>GridViewAction::class,
                'searchModel'=><?= isset($searchModelAlias)?$searchModelAlias:$searchModelClass ?>::class,
                //'smallTitle'=>'Список',
            ],
            'create'=>[
                'class'=>SaveModelAction::class,
                'modelForm'=><?= isset($formModelAlias)?$formModelAlias:$formModelClass ?>::class,
                'model'=><?= $modelClass ?>::class,
                'isNewRecord'=>true,
            ],
            'update'=>[
                'class'=>SaveModelAction::class,
                'modelForm'=><?= isset($formModelAlias)?$formModelAlias:$formModelClass ?>::class,
                'model'=><?= $modelClass ?>::class,
                'isNewRecord'=>false,
            ],
        ];
    }


    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        //TODO реализуйте метод удаления данных
        //$this->findModel()->delete();

        //return $this->redirect(['index']);
    }
}
