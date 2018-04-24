<?php

namespace app\modules\rating\install\migrations;

use app\modules\core\components\Migration;

class m180419_075508_rating_period_create extends Migration
{
    protected $table = '{{rating__period}}';

	public function safeUp()
	{
		$this->createTable(
			$this->table,
			[
				'id'		=> $this->primaryKey(),
				'title'		=> $this->string(255)->notNull(),
				'status'	=> $this->integer()->notNull()->defaultValue(0),
			],
			$this->getOptions()
		);

        $this->createDateColumns();

        $this->createIndex("ix_{$this->tableName}_status", $this->table, "status");
	}

	public function safeDown()
	{
		$this->dropTable($this->table);
	}
}