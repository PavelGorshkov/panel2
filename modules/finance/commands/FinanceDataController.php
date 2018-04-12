<?php

namespace app\modules\finance\commands;

use app\modules\finance\helpers\FinanceParser;
use app\modules\finance\models\BalanceParser;
use app\modules\finance\models\dictionary\Kvd;
use \yii\console\Controller;
use app\modules\core\interfaces\RegisterCommandInterface;
use yii\console\ExitCode;

/**
 * Class FinanceDataController
 * @package app\modules\finance\commands
 */
class FinanceDataController extends Controller implements RegisterCommandInterface{


    /**
    * Получение списка команд в контроллере, которые будут участвовать в заданиях cron-модуля
    * @return array
    */
    public static function getList(){
        return [
            'dictionary' => 'Заполнение справочников',
            'balance' => 'Заполнение финансовых остатков'
        ];
    }


    /**
     * Заполнение справочников
     * @return int
     * @throws \Throwable
     */
    public function actionDictionary(){
        //Здесь неточность в архитектуре парсера. Невозможно использовать класс DictionaryBase, т.к. он абстрактный.
        //Но можно использовать любой справочник, который является потомком DictionaryBase.
        return $this->_parse(Kvd::class);
    }


    /**
     * Заполнение финансовых остатков
     * @return int
     * @throws \Throwable
     */
    public function actionBalance(){
        return $this->_parse(BalanceParser::class);
    }


    /**
     *
     * @param string $parser
     * @return int
     * @throws \Throwable
     */
    private function _parse($parser){
        try{
            $status = FinanceParser::parse($parser);
            $this->stdout(implode(' ', $status));
            return ExitCode::OK;
        }
        catch(\Exception $error){
            $this->stderr($error->getMessage());
            $code = $error->getCode();

            return ($code !== ExitCode::OK && isset(ExitCode::$reasons[$code]))
                ? $error->getCode()
                : ExitCode::UNSPECIFIED_ERROR;
        }
    }

}
