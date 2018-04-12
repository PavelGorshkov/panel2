<?php

namespace app\modules\core\install\migrations;

use app\modules\core\components\Migration;

/**
 * Class m180403_083208_core_settings_rename_table
 * @package app\modules\core\install\migrations
 */
class m180403_083208_core_settings_rename_table extends Migration
{
    protected $table = '{{%core_settings}}';

    /**
     * @return bool|void
     */
	public function safeUp()
	{
        $this->renameTable($this->table, '{{%core__settings}}');
	}

    /**
     * @return bool|void
     */
	public function safeDown()
	{
        $this->renameTable('{{%core__settings}}', $this->table);
	}
}