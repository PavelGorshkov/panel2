<?php

namespace app\modules\user\install\migrations;

use app\modules\core\components\Migration;

/**
 * Class m171222_093935_user_access_create
 * @package app\modules\user\install\migrations
 */
class m171222_093935_user_access_create extends Migration
{
    protected $table = '{{%user_access}}';

    /**
     * @return bool|void
     */
	public function safeUp()
	{
        $this->createTable($this->table,[
            'access'=>$this->string(100)->notNull(),
            'type'=>$this->boolean()->notNull(),
            'id'=>$this->integer()->notNull(),
        ],$this->getOptions());


        $this->createIndex("ux_{$this->tableName}_access_type_id", $this->table, 'access, type, id', true);
        $this->createIndex("ix_{$this->tableName}_type_id", $this->table, 'type, id', false);
	}


    /**
     * @return bool|void
     */
	public function safeDown()
	{
        $this->dropTable($this->table);
	}
}