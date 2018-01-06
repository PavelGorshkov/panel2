<?php
namespace app\modules\core\helpers;

use Yii;
use yii\web\ServerErrorHttpException;


trait ConfigCacheTrait
{
    /**
     * @param array $data
     * @return bool
     *
     * @throws ServerErrorHttpException
     */
    public function createCache(array $data) {

        // Если выключена опция кеширования настроек - не выполняем его:
        if ($this->isDebug()) return true;

        if (!@file_put_contents($this->getCacheFile(), $this->getFileTemplateJSON($data), LOCK_EX)) {
            throw new ServerErrorHttpException('Ошибка записи кеша в файл '.$this->getCacheFile().' в классе "'.__CLASS__.'"');
        }

        return true;
    }


    /**
     * Проверка debug режима
     *
     * @return bool
     */
    private function isDebug() {

        return (defined('\YII_DEBUG') && \YII_DEBUG === true)
            || (defined('YII_ENV') && YII_ENV === 'dev');
    }


    /**
     * @return string
     *
     * @throws ServerErrorHttpException
     */
    private function getCacheFile() {

        if (!File::checkPath($this->getCachePath())) {

            throw new ServerErrorHttpException('Check rights path: '.$this->getCachePath());
        };

        return Yii::getAlias(sprintf("%s/%s.json", $this->getCachePath(), $this->env));
    }


    /**
     * путь хранения директории cache конфига
     *
     * @return bool|string
     */
    private function getCachePath() {

        return Yii::getAlias('@app/runtime/config');
    }


    /**
     * Шаблон генерации json файла
     *
     * @param array $data
     * @return string
     */
    private function getFileTemplateJSON(array $data) {

        return json_encode($data);
    }


    /**
     * @return bool
     * @throws ServerErrorHttpException
     */
    private function isCached() {

        if ($this->isDebug()) return false;

        return file_exists($this->getCacheFile());
    }


    /**
     * Очистка cache конфига
     */
    public function flushCache() {

        if (is_dir($this->getCachePath())) {

            File::rmDir($this->getCachePath().DIRECTORY_SEPARATOR.'*');
        }
    }


    /**
     * Возвращает закешированный конфиг
     *
     * @return array|null
     * @throws ServerErrorHttpException
     */
    public function getCacheSettings() {

        return $this->isCached()?$this->loadCache():null;
    }


    /**
     * Загрузка из кэша
     *
     * @return array|null
     * @throws ServerErrorHttpException
     */
    private function loadCache()
    {
        $data = @json_decode(file_get_contents($this->getCacheFile()), 1);

        if (is_array($data) === false) {$data = null;}

        return $data;
    }
}