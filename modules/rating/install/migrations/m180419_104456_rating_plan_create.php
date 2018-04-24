<?php

namespace app\modules\rating\install\migrations;

use app\modules\core\components\Migration;

class m180419_104456_rating_plan_create extends Migration
{
    protected $table = '{{rating__plan}}';

	public function safeUp()
	{
		$this->createTable(
			$this->table,
			[
				'id'				=> $this->primaryKey(),
				'period_id'			=> $this->integer()->notNull(),
				'section_id'		=> $this->integer()->notNull()->defaultValue(0),
				'indicator_id'		=> $this->integer()->notNull()->defaultValue(0),
				'subindicator_id'	=> $this->integer()->notNull()->defaultValue(0),
		        'points'			=> $this->integer()->null(),
		        'weight'			=> $this->float()->notNull()->defaultValue(0),
			],
			$this->getOptions()
		);

        $this->createDateColumns();

        $this->createIndex("ix_{$this->tableName}_period_section_indicator_subindicator", $this->table, "period_id, section_id, indicator_id, subindicator_id");
        $this->createIndex("ix_{$this->tableName}_subindicator_id", $this->table, "subindicator_id");
        $this->createIndex("ix_{$this->tableName}_indicator_id", $this->table, "indicator_id");
        $this->createIndex("ix_{$this->tableName}_section_id", $this->table, "section_id");
        $this->createIndex("ix_{$this->tableName}_period_id", $this->table, "period_id");
	}

	public function safeDown()
	{
		$this->dropTable($this->table);
	}
}