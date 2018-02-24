<?php
namespace app\modules\core\helpers;

/**
 * Class LoggerHelper
 * @package app\modules\core\helpers
 * @method static LoggerHelper model()
 */
class LoggerHelper
{
    use SingletonTrait;

    /**
     * @var string logPath
     */
    private $path = "@runtime/logs/";

    /**
     * const sourceType
     */
    const SOURCE_FILE = "files";

    /**
     * Получение доступных логов
     */
    public function initData()
    {
        $this->_data = [
            self::SOURCE_FILE => $this->getLogsFiles(),
        ];
    }

    /**
     * Получение списка файлов логов
     * @return array files
     */
    public function getLogsFiles()
    {
        $path = \Yii::getAlias($this->path);
        $array = [];
        $iterator = new \GlobIterator($path . '*.log');
        foreach ($iterator as $item) {
            $array[$item->getFilename()] = [
                'filePath' => $item->getPathname(),
                'name' => $item->getFilename(),
                'modTime' => $item->getATime(),
            ];
        }
        return $array;
    }


    /**
     * @return array
     */
    public function getSourceType()
    {
        return [
            self::SOURCE_FILE => "Файл",
        ];
    }


    /**
     * @param string $name
     * @return array|null
     */
    public function getLogExist($name)
    {
        $logData = $this->getData();
        foreach ($logData as $source => $logs) {

            if (isset($logs[$name])) {

                $info = new LoggerDetailInfo($source);
                $data = $info->getDetailInfo($logs[$name]);
                return $data;
            }
        }
        return null;
    }
}