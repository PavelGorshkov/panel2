<?php

namespace app\modules\cron\commands;

use app\modules\core\interfaces\RegisterCommandInterface;
use yii\console\Controller;

/**
 * Class TestController
 * @package app\modules\cron\commands
 */
class TestController extends Controller implements RegisterCommandInterface
{

    /**
     * Получение списка команд в контроллере, которые будут участвовать в заданиях cron-модуля
     * @return array
     */
    public static function getList(){
        return [
            'test' => 'Тестовый экшен команды'
        ];
    }


    /**
     * Тестовый экшен
     */
    public function actionTest(){
        //Какие-то непонытные действия

        echo 'Вывод результата команды';
    }
}
