<?php

namespace app\modules\user\install\migrations;

use app\modules\core\components\Migration;

class m171222_093320_user_role_create extends Migration
{
    protected $table = '{{%user_role}}';

	public function safeUp()
	{
        $this->createTable($this->table, [
                'id'=>$this->primaryKey(),
                'title'=>$this->string(50)->null(),
                'description'=>$this->text()->null(),
            ],
            $this->getOptions()
        );

        $this->createDateColumns();
        $this->createUserColumns();

        $this->execute("ALTER TABLE {$this->table} AUTO_INCREMENT = 100");
	}

	public function safeDown()
	{
        $this->dropTable($this->table);
	}
}