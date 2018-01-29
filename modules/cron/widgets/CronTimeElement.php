<?php

namespace app\modules\cron\widgets;

use app\modules\core\components\FormModel;
use app\modules\cron\interfaces\TimeParamInterface;
use yii\web\HttpException;
use yii\widgets\InputWidget;

/**
 * Class CronTimeElement
 * @package app\modules\user\models
 */
class CronTimeElement extends InputWidget
{
    /**
     * @var FormModel
     */
    public $model = null;


    /**
     * Количество столбцов, в которых будут расположены элементы. До 12 столбцов.
     * @var integer
     */
    public $columnCount = 1;


    /**
     * Вьюха для виджета
     * @var string
     */
    protected $view = 'element';


    /**
     * Данные для checkboxList
     * @var array
     */
    protected $data = [];


    /**
     * Стиль сетки для столбцов
     * @var string
     */
    protected $colStyle  = '';


    /**
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();

        if(!$this->attribute){
            throw new HttpException(404, 'Unknown attribute.');
        }

        if($this->model === null){
            throw new HttpException(404, 'Model not found.');
        }

        if(!($this->model instanceof TimeParamInterface)){
            throw new HttpException(404, 'Widget used bad model.');
        }

        //Проверка количества столбцов
        if($this->columnCount > 12){ $this->columnCount = 12; }      
        if($this->columnCount <= 0){ $this->columnCount = 1; }        

        //Бредовый велосипед для отображения данных во вьюхе
        $this->columnCount = 12/round(12/$this->columnCount, 0);
        $this->colStyle  = 'col-sm-'.round(12/$this->columnCount, 0);
        
        //Преобразование данных для удобства отображения во вьюхе
        $i = 0;
        $j = 0;
        foreach ($this->model->getTimeData($this->attribute) as $key => $value) {
            if($i == $this->columnCount){
                $i = 0;
                $j = $j + 1;
            }
            
            $this->data[$j][$key] = $value;
            $i = $i + 1;
        }
    }
    

    /**
     * @return string
     */
    public function run() {
        return $this->render($this->view, [
                'model' => $this->model,
                'data' => $this->data,
                'colStyle' => $this->colStyle,
                'columnCount' => $this->columnCount,
                'attribute' => $this->attribute
            ]
        );
    }

}
