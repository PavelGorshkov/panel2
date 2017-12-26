<?php
namespace app\modules\core\helpers;

use yii\helpers\FileHelper;

/**
 * Класс хелпер для работы с файлами приложения
 *
 * Class File
 * @package app\modules\core\helpers
 */
class File extends FileHelper {

    /**
     * Проверка пути к директории. Если дириктории нет, создает директорию
     *
     * @param string $path
     * @param int $rights
     * @param bool $recursive
     *
     * @return bool
     */
    public static function checkPath($path, $rights = 0777, $recursive = true)
    {
        if(empty($path)) {return false;}

        if (!is_dir($path)) { // проверка на существование директории

            $mask = umask(0);
            $is = mkdir($path, $rights, $recursive); // возвращаем результат создания директории
            umask($mask);

            return $is;

        } else {
            if (!is_writable($path)) { // проверка директории на доступность записи

                return false;
            }
        }

        return true; // папка существует и доступна для записи
    }


    /**
     * Копипрование файла в директорию с нужными правами
     *
     * @param string $from
     * @param string $to
     * @param int $mode
     *
     * @return bool
     */
    public static function cpFile($from, $to, $mode = 0777)
    {
        $u_mask = umask(0);
        if ($status = copy($from, $to)) chmod($from, $mode);
        umask($u_mask);

        return $status;
    }


    /**
     * Рекрусивное удаление директорий.
     *
     * @param string $path Если $path оканчивается на *, то удаляется только содержимое директории.
     * @since 0.5
     * @return bool
     */
    public static function rmDir($path)
    {
        static $doNotRemoveBaseDirectory = false, $baseDirectory;

        $path = trim($path);
        if (substr($path, -1) == '*') {
            $doNotRemoveBaseDirectory = true;
            $path = substr($path, 0, -1);
        }
        if (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }
        if ($doNotRemoveBaseDirectory) {
            $baseDirectory = $path;
        }

        if (is_dir($path)) {
            $dirHandle = opendir($path);
            while (false !== ($file = readdir($dirHandle))) {
                if ($file != '.' && $file != '..') {
                    $tmpPath = $path.'/'.$file;

                    if (is_dir($tmpPath)) {
                        self::rmDir($tmpPath);
                    } else {
                        if (file_exists($tmpPath)) {
                            unlink($tmpPath);
                        }
                    }
                }
            }
            closedir($dirHandle);

            // удаляем текущую папку
            if ($doNotRemoveBaseDirectory === true && $baseDirectory == $path) {
                return true;
            }

            return rmdir($path);

        } elseif (is_file($path) || is_link($path)) {

            return unlink($path);
        } else {

            return false;
        }
    }
}