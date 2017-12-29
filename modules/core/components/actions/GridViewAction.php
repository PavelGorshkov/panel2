<?php
namespace app\modules\core\components\actions;


use app\modules\core\interfaces\SearchModelInterface;
use Yii;
use yii\web\ServerErrorHttpException;

class GridViewAction extends WebAction
{
    public $searchModel = null;

    /**
     * @var SearchModelInterface
     */
    protected $model = null;


    public function init() {

        parent::init();

        if ($this->searchModel === null)
            throw new ServerErrorHttpException('In action '.$this->id.' in controller '.$this->controller->id.' not found param searchModel');

        $this->model = Yii::createObject([
            'class'=>$this->searchModel,
        ]);

        if ($this->model === null)
            throw new ServerErrorHttpException('In action '.$this->id.' not found search model');

        if (!($this->model instanceof SearchModelInterface))
            throw new ServerErrorHttpException('In action '.$this->id.' search model '.$this->searchModel.' not implements interface \\app\\modules\\core\\interfaces\\SearchModelInterface');
    }


    public function run() {

        $dataProvider = $this->model->search(app()->request->get());

        return $this->render([
            'dataProvider' => $dataProvider,
            'searchModel' => $this->model,
            'module' => $this->controller->module,
        ]);
    }
}