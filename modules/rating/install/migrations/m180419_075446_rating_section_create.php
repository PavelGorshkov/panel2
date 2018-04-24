<?php

namespace app\modules\rating\install\migrations;

use app\modules\core\components\Migration;

class m180419_075446_rating_section_create extends Migration
{
    protected $table = '{{rating__section}}';

	public function safeUp()
	{
		$this->createTable(
			$this->table,
			[
				'id'		=> $this->primaryKey(),
				'title'		=> $this->string(255)->notNull()
			],
			$this->getOptions()
		);

        $this->createDateColumns();
	}

	public function safeDown()
	{
		$this->dropTable($this->table);
	}
}