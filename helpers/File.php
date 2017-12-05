<?php
namespace app\helpers;


class File extends \yii\helpers\FileHelper {

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
}