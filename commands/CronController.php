<?php

namespace app\commands;

use app\modules\core\components\ConsoleRunner;
use app\modules\cron\models\RunnerJob;
use yii\console\Controller;

/**
 * Class CronController
 * @package app\commands
 */
class CronController extends Controller{

    /**
     * Запуск всех активных команд по крону
     */
    public function actionIndex(){
        /** @var ConsoleRunner $runner */
        /** @var RunnerJob $job */

        $jobs = RunnerJob::find()->active();

        if($jobs){
            $runner = app()->consoleRunner;
            foreach($jobs as $job){
                if($job->checkTime()){
                    $runner->run($job->command, [], true);
                }
            }
        }
    }
}
