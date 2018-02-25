<?php
namespace app\modules\core\helpers;

use yii\base\Exception;

/**
 * Class TranslitHelper
 * @package app\modules\core\helpers
 */
class TranslitHelper
{
    protected static $rus = [
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И',
        'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т',
        'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь',
        'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё',
        'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ',
        'ъ', 'ы', 'ь', 'э', 'ю', 'я'
    ];

    protected static $lat = [
        'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I',
        'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T',
        'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y',
        'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e',
        'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o',
        'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh',
        'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'
    ];


    /**
     * @param string $str
     * @return string
     */
    protected static function route($str)
    {
        return (preg_match('#[A-Za-z]+#', $str))
            ? 'en-ru'
            : 'ru-en';
    }


    /**
     * @param $str
     * @param bool $no_space
     * @param string $route
     * @return mixed
     * @throws Exception
     */
    public static function translit($str, $no_space = true, $route = '')
    {
        if (empty($route)) {

            $route = self::route($str);
        }

        if ($route == 'en-ru') {
            $string = str_replace(self::$lat, self::$rus, $str);
        } elseif ($route == 'ru-en') {
            $string = str_replace(self::$rus, self::$lat, $str);
        } else {
            throw new Exception('Неизвестное направление транслитерации');
        }
        if ($no_space === true) {
            $string = str_replace(' ', '_', $string);
        }

        return $string;
    }
}