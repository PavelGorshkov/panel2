<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 20.12.2017
 * Time: 15:27
 */

namespace app\modules\core\components;


use yii\base\Model;


class FormModel extends Model {

    /**
     * Метод хранящий описания атрибутов:
     *
     * @return array описания атрибутов
     **/
    public function attributeDescriptions()
    {
        return $this->attributeLabels();
    }

    /**
     * Метод получения описания атрибутов
     *
     * @param string $attribute - id-атрибута
     *
     * @return string описания атрибутов
     **/
    public function getAttributeDescription($attribute)
    {
        $descriptions = $this->attributeDescriptions();

        return (isset($descriptions[$attribute])) ? $descriptions[$attribute] : '';
    }

}