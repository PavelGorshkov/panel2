<?php

namespace app\modules\cron\install\migrations;

use app\modules\core\components\Migration;

/**
 * Class m180117_114635_cron_job_create
 * @package app\modules\cron\install\migrations
 */
class m180117_114635_cron_job_create extends Migration
{
    protected $table = '{{%cron_job}}';

    /**
     * @return bool|void
     */
	public function safeUp()
	{
        $this->createTable(
            $this->table,
            [
                'id' => $this->primaryKey(),
                'command' => $this->string(255)->notNull(),
                'is_active' => $this->boolean()->notNull()->defaultValue('0'),
                'params' => $this->string(255)->notNull()
            ],
            $this->getOptions('InnoDB')
        );

        $this->createDateColumns();
	}

    /**
     * @return bool|void
     */
	public function safeDown()
	{
        $this->dropTable($this->table);
	}
}