<?php

namespace app\modules\user\install\migrations;

use app\modules\core\components\Migration;

/**
 * Class m171220_163014_user_token_create_table
 * @package app\modules\user\install\migrations
 */
class m171220_163014_user_token_create_table extends Migration
{
    protected $table = '{{%user_token}}';

    /**
     * @return bool|void
     */
	public function safeUp()
	{
        $this->createTable($this->table, [
            'id'=>$this->primaryKey(),
            'user_id'=>$this->integer()->notNull(),
            'token'=>$this->string(255)->null(),
            'type'=>$this->boolean()->null(),
            'status'=>$this->boolean()->notNull()->defaultValue('0'),
            'ip'=>$this->integer()->null(),
            'expire'=>$this->dateTime()->notNull(),
        ], $this->getOptions('InnoDB'));

        $this->createDateColumns();
        $this->createUserColumns();

        $table = '{{%user_user}}';

        $this->addForeignKey(
            "fx_{$this->gettableName()}",
            $this->table,
            'user_id',
            $table,
            'id',
            $this->cascade,
            $this->restrict
        );
	}


    /**
     * @return bool|void
     */
	public function safeDown()
	{
        $this->dropTable($this->table);
	}
}