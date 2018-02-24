<?php
namespace app\modules\core\components\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Class ModelWebUserBehavior
 * @package app\modules\core\components\behaviors
 */
class ModelWebUserBehavior extends AttributeBehavior
{

    public $createdAtAttribute = 'created_by';

    /**
     * @var string the attribute that will receive timestamp value.
     * Set this property to false if you do not want to record the update time.
     */
    public $updatedAtAttribute = 'updated_by';

    public $value = null;

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {

            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
            ];
        }
    }


    /**
     * @param \yii\base\Event $event
     * @return int|mixed|string
     */
    protected function getValue($event)
    {
        if ($this->value === null) {

            return user()->id;
        }

        return parent::getValue($event);
    }
}