<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\modules\core\components\OutputMessage;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RunController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }


    /**
     * Обновление через консоль.
     */
    public function actionMigrate()
    {
        try {
            app()->migrator->updateToLatestSystem();

            $this->getMessages();

        } catch (Exception $e) {

            $this->getMessages();

            $this->stdout($e->getMessage()."\n", Console::FG_RED);
        }
    }


    /**
     * Вывод сообщение мигратора
     */
    protected function getMessages() {

        /* @var $message OutputMessage */
        foreach (app()->migrator->getConsole() as $message) {

            $this->stdout($message->getMessage()."\n", $message->getType(true));
        }
    }
}
