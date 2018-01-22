<?php
namespace app\modules\developer\models;

use yii\base\Model;

/**
 * Class Migration
 * @package app\modules\developer\models
 */
class Migration extends Model
{
    public $id;

    public $module;

    public $className;

    public $createTime;
}