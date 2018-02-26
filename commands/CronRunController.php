<?php

namespace app\commands;

use app\modules\core\components\ConsoleRunner;
use app\modules\cron\models\RunnerJob;
use yii\console\Controller;

/**
 * Class CronController
 * @package app\commands
 */
class CronRunController extends Controller
{

    /**
     * Запуск всех активных команд по крону
     */
    public function actionIndex()
    {
        /** @var ConsoleRunner $runner */
        /** @var RunnerJob $job */
        

        $jobs = RunnerJob::find()->active();

        if ($jobs) {
            $runner = app()->consoleRunner;
            foreach ($jobs as $job) {
                if ($job->checkTime()) {
                    $runner->run($job->command, [], true);
					file_put_contents(\Yii::getAlias('@app/runtime/logs').'/console.php', date('Y-m-d H:i:s'));
                }
            }
        }
    }
}
