<?php

namespace app\modules\progress\helpers;

use app\modules\progress\models\Dictionary;

/**
 * Class Digest
 * @package app\modules\progress\helpers
 */
class Digest{

    /**
     * Данные справочников
     * @var array
     */
    private static $_data = [];


    /**
     * Создание справочника
     * @param string $dictionaryName
     */
    private static function _createDictionary($dictionaryName){

        $list = array_flip(Dictionary::getDictionaryList());

        if(isset($list[$dictionaryName])){
            if(!isset(self::$_data[$dictionaryName])){

                self::$_data[$dictionaryName] = [];

                foreach(Dictionary::findAll(['dictionary' => $dictionaryName]) as $item){
                    /** @var Dictionary $item */
                    $attributes = $item->getAttributes();
                    unset($attributes['created_at'], $attributes['updated_at']);

                    self::$_data[$dictionaryName][$attributes['uid']] = $attributes;
                }
            }
        }
    }


    /**
     * Получение данных справочника
     * @param string $dictionaryName
     * @return array
     */
    public static function getItems($dictionaryName){
        self::_createDictionary($dictionaryName);
        return isset(self::$_data[$dictionaryName]) ? self::$_data[$dictionaryName] : [];
    }


    /**
     * Получение атрибутов из справочника по uid
     * @param string $dictionaryName
     * @param string $uid
     * @return array
     */
    public static function getItem($dictionaryName, $uid){
        self::_createDictionary($dictionaryName);
        return isset(self::$_data[$dictionaryName][$uid]) ? self::$_data[$dictionaryName][$uid] : [];
    }


    /**
     * Получение значения из справочника по uid
     * @param string $dictionaryName
     * @param string $uid
     * @return string
     */
    public static function getItemValue($dictionaryName, $uid){
        $item = self::getItem($dictionaryName, $uid);
        return isset($item['value']) ? $item['value'] : '';
    }


    /**
     * Получение родительского элемента
     * @param string $dictionaryName
     * @param string $uid
     * @return array
     */
    public static function getParent($dictionaryName, $uid){
        $item = self::getItem($dictionaryName, $uid);
        return isset($item['parent_uid']) ? self::getItem($dictionaryName, $item['parent_uid']) : [];
    }


    /**
     * Получение цепочки родителей текущего элемента
     * @param string $dictionaryName
     * @param string $uid
     * @return array
     */
    public static function getParentChain($dictionaryName, $uid){
        $chain = [];

        do{
            if(!($item = self::getItem($dictionaryName, $uid)) || ($item['parent_uid'] == Dictionary::EMPTY_UID)){
                break;
            }

            $uid = $item['parent_uid'];
            $chain[] = $uid;
        }while(true);

        return $chain;
    }
}