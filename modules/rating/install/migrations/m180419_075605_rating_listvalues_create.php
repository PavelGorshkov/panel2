<?php

namespace app\modules\rating\install\migrations;

use app\modules\core\components\Migration;

class m180419_075605_rating_listvalues_create extends Migration
{
    protected $table = '{{rating__list__value}}';

	public function safeUp()
	{
		$this->createTable(
			$this->table,
			[
				'id'		=> $this->primaryKey(),
				'title'		=> $this->string(255)->notNull(),
				'list_id'	=> $this->integer()->notNull()->defaultValue(0),
		        'points'	=> $this->integer()->null(),
		        'weight'	=> $this->float()->notNull()->defaultValue(0),
		        'classname'	=> $this->string(255)->null(),
			],
			$this->getOptions()
		);

        $this->createDateColumns();

        $this->createIndex("ix_{$this->tableName}_list_id", $this->table, "list_id");
	}

	public function safeDown()
	{
		$this->dropTable($this->table);
	}
}