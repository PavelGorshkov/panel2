<?php

namespace app\modules\finance\helpers;

use app\modules\finance\interfaces\DictionaryInterface;
use app\modules\finance\models\dictionary\{
    Activity, Classifier, DictionaryBase, Kbk, Kosgu, Kvd, Unit
};

/**
 * Class Dictionary
 * @package app\modules\finance\helpers
 */
class Dictionary
{
    const DICTIONARY_ACTIVITY = 'activity';
    const DICTIONARY_CLASSIFIER = 'classifier';
    const DICTIONARY_KBK = 'kbk';
    const DICTIONARY_KOSGU = 'kosgu';
    const DICTIONARY_KVD = 'kvd';
    const DICTIONARY_UNIT = 'unit';

    /**
     * Привязка к модели справочника
     * @var array
     */
    private static $_dictionaryModels = [

        self::DICTIONARY_ACTIVITY => Activity::class,
        self::DICTIONARY_CLASSIFIER => Classifier::class,
        self::DICTIONARY_KBK => Kbk::class,
        self::DICTIONARY_KOSGU => Kosgu::class,
        self::DICTIONARY_KVD => Kvd::class,
        self::DICTIONARY_UNIT => Unit::class
    ];


    /**
     * Данные справочников
     * @var array
     */
    private static $_data = [];


    /**
     * @param string $dictionary
     */
    private static function _init($dictionary)
    {
        if (isset(self::$_dictionaryModels[$dictionary])) {

            /** @var DictionaryInterface $model */
            $model = new self::$_dictionaryModels[$dictionary];

            if ($model instanceof DictionaryInterface) {

                self::$_data[$dictionary] = $model->getItems();
            }
        }
    }


    /**
     * Получение всех значений справочника
     * @param string $dictionary
     * @return array
     */
    public static function getItems($dictionary)
    {
        if (!isset(self::$_data[$dictionary])) {
            self::_init($dictionary);
        }

        return self::$_data[$dictionary] ?? [];
    }


    /**
     * Получение значения справочника по uid
     * @param string $dictionary
     * @param string $uid
     * @return array
     */
    public static function getItem($dictionary, $uid)
    {
        if (!isset(self::$_data[$dictionary])) {
            self::_init($dictionary);
        }

        return self::$_data[$dictionary][$uid] ?? ['value' => $uid];
    }


    /**
     * Получение цепочки родителей текущего элемента
     * @param string $dictionary
     * @param string $uid
     * @return array
     */
    public static function getParentChain($dictionary, $uid)
    {
        $chain = [];

        do {
            if (!($item = self::getItem($dictionary, $uid)) || ($item['parent_uid'] == DictionaryBase::EMPTY_UID)) {
                break;
            }

            $uid = $item['parent_uid'];
            $chain[] = $uid;

        } while (true);

        return $chain;
    }
}