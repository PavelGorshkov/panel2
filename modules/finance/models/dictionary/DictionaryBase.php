<?php

namespace app\modules\finance\models\dictionary;

use app\modules\core\components\UisActiveRecord;
use app\modules\finance\interfaces\DictionaryInterface;

/**
 * Class DictionaryBase
 * @package app\modules\finance\models\dictionary
 *
 * @property string $uid
 * @property string $value
 * @property string $parent_uid
 * @property string $created_at
 * @property string $updated_at
 */
abstract class DictionaryBase extends UisActiveRecord implements DictionaryInterface
{
    const EMPTY_UID = '00000000-0000-0000-0000-000000000000';


    /** @var string */
    public $primaryKey = 'uid';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'parent_uid'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 500],
            [['uid'], 'unique'],
        ];
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        return false;
    }


    /**
     * Получение списка всех значений справочника
     * @return array
     */
    public function getItems(): array
    {
        $data = [];

        foreach (self::find()->asArray()->all() as $item) {

            unset($item['created_at'], $item['updated_at']);

            $data[$item[$this->primaryKey]] = $item;
        }

        return $data;
    }
}