<?php

namespace app\modules\finance\models;

use yii\behaviors\TimestampBehavior;
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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance__balance}}';
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
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param null $year
     * @return array
     */
    public function getActualYears($year)
    {
        $range = self::find()
            ->select([
                new Expression('MAX(YEAR(creating_date)) max_year'),
                new Expression('MIN(YEAR(creating_date)) min_year')
            ])
            ->asArray()
            ->groupBy('YEAR(creating_date)')
            ->column();

        $max = $range['max_year']??(integer)date('Y');
        $min = $range['min_year']??(integer)date('Y');

        $current = (!$year || ($year > $max)) ? $max : $year;

        if (date('n') > 8) {
            $max = $max + 1;
        }

        return [$min, $max, $current];
    }
}
