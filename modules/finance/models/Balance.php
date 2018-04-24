<?php

namespace app\modules\finance\models;

use app\modules\finance\helpers\Dictionary;
use yii\base\InvalidConfigException;
use yii\db\Expression;

/**
 * This is the model class for table "{{%finance_balance}}".
 *
 * @property int $kvd_id
 * @property string $begin_value
 * @property string $end_value
 * @property string $creating_date
 * @property string $invoice
 * @property string $kbk
 * @property string $created_at
 * @property string $updated_at
 */
class Balance extends Finance
{
    protected $_last_day = null;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kvd_id' => 'КВД',
            'begin_value' => 'Денежные средства на начало дня',
            'end_value' => 'Денежные средства на конец дня',
            'creating_date' => 'Дата',
            'invoice' => 'Счёт',
            'kbk' => 'КБК',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kvd_id', 'begin_value', 'end_value', 'creating_date'], 'required'],
            [['kvd_id'], 'integer'],
            [['begin_value', 'end_value'], 'number'],
            [['invoice', 'kbk', 'created_at', 'updated_at'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance__balance}}';
    }


    /**
     * @return bool
     */
    public function findLastDay()
    {
        $this->_last_day = self::find()
            ->select([new Expression('MAX(creating_date)')])
            ->asArray()
            ->scalar();

        return !empty($this->_last_day);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function getLastDay()
    {
        if (empty($this->_last_day)) return '';

        return app()->formatter->asDate($this->_last_day, 'long');
    }

    /**
     * @return array
     */
    public function getDataForLastDay()
    {
        if (empty($this->_last_day)) return [];

        $data = self::find()
            ->asArray()
            ->select(
                [
                    'kvd_id',
                    new Expression('SUM(begin_value) begin'),
                    new Expression('SUM(end_value) end')
                ])
            ->groupBy('kvd_id')
            ->where(['creating_date'=>$this->_last_day])
            ->all();

        return $data;
    }
}
