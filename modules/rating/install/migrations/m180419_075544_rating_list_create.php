<?php

namespace app\modules\rating\install\migrations;

use app\modules\core\components\Migration;

class m180419_075544_rating_list_create extends Migration
{
    protected $table = '{{rating__list}}';

	public function safeUp()
	{
		$this->createTable(
			$this->table,
			[
				'id'		=> $this->primaryKey(),
				'title'		=> $this->string(255)->notNull(),
				'is_const'	=> $this->boolean()->notNull()->defaultValue(1),
		        'period_id'	=> $this->integer()->null(),
		        'type'		=> $this->integer()->defaultValue(0),
			],
			$this->getOptions()
		);

        $this->createDateColumns();

        $this->createIndex("ix_{$this->tableName}_is_const", $this->table, "is_const");
        $this->createIndex("ix_{$this->tableName}_type", $this->table, "type");
        $this->createIndex("ix_{$this->tableName}_period_id", $this->table, "period_id");
	}

	public function safeDown()
	{
		$this->dropTable($this->table);
	}
}