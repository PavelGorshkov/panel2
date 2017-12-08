<?php

use app\modules\core\components\Migration;

class m170122_202817_create_core_setting extends Migration
{
    protected $table = '{{%core_settings}}';

	public function safeUp()
	{
		$this->createTable(
			$this->table,
			[
				'id'                => $this->primaryKey(),
				'module'            => $this->string(50)->notNull()->defaultValue('userdata'),
				'param_name'        => $this->string(100)->notNull(),
				'param_value'       => $this->string(500)->notNull(),
				'user_id'           => $this->integer()->notNull()->defaultValue('0'),
			],
			$this->getOptions()
		);

        $this->createDateColumns();

        $this->createIndex("ux_{$this->tableName}_module_param_user", $this->table, "module, param_name, user_id", true);
		$this->createIndex("ix_{$this->tableName}_module_param", $this->table, "param_name");
		$this->createIndex("ix_{$this->tableName}_module", $this->table, "module");
	}


	public function safeDown()
	{
		$this->dropIndex("ix_{$this->tableName}_module", $this->table);
		$this->dropIndex("ix_{$this->tableName}_module_param", $this->table);
		$this->dropIndex("ux_{$this->tableName}_module_param_user", $this->table);

		$this->dropTable($this->table);
	}
}