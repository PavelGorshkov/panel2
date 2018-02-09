<?php

namespace app\modules\core\helpers;

use yii\base\Exception;

/**
 * Class LoggerDetailInfo
 * @package app\modules\core\helpers
 */
class LoggerDetailInfo
{

    const SOURCE_FILE = "files";
    const SOURCE_DB = "db";

    private $class;

    /**
     * @return array
     */
    private function getSourceTypeClass()
    {

        return [
            self::SOURCE_FILE => LoggerFileParser::className(),
        ];

    }

    /**
     * LoggerDetailInfo constructor.
     * @param $source
     * @throws Exception
     */
    public function __construct($source)
    {
        $types = $this->getSourceTypeClass();
        if (empty($types[$source]))
            throw new Exception("Реализуйте метод обработки для данного источника");
        else
            $this->class = new $types[$source];
    }

    /**
     * @param array $log
     * @return array
     */
    public function getDetailInfo($log)
    {
        return $this->class->getData($log);
    }

}
