<?php

namespace app\modules\user\install\migrations;

use app\modules\core\components\Migration;
use yii\db\Exception;

/**
 * Class m180313_170433_update_user_data
 * @package app\modules\user\install\migrations
 */
class m180313_170433_update_user_data extends Migration
{
    protected $table = '{{%user__info}}';

    protected $profile = '{{%user__profile}}';

    /**
     * @return bool|void
     * @throws Exception
     */
    public function safeUp()
    {
        $this->renameTable('{{%user_user}}', $this->table);
        $this->renameTable('{{%user_access}}', '{{%user__access}}');
        $this->renameTable('{{%user_role}}', '{{%user__role}}');
        $this->renameTable('{{%user_token}}', '{{%user__token}}');

        $this->alterColumn($this->table, 'user_ip', $this->bigInteger()->null());
        $this->dropColumn($this->table, 'logged_in_from');
        $this->dropColumn($this->table, 'logged_at');

        $this->createTable($this->profile, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'full_name' => $this->string(150)->null(),
            'department' => $this->string(150)->null(),
            'phone' => $this->char(30)->null(),
        ], $this->getOptions('InnoDB'));

        $this->createDateColumns($this->profile);

        $data = app()->db->createCommand(/** @lang text */
            <<<SQL
            SELECT id as user_id, full_name, about as department, phone
              FROM {{%user__info}}
             WHERE status = :status
          ORDER BY id
SQL
            , [':status' => 1])->queryAll();

        $this->batchInsert($this->profile, ['user_id', 'full_name', 'department', 'phone'], $data);

        $this->dropColumn($this->table, 'full_name');
        $this->dropColumn($this->table, 'about');
        $this->dropColumn($this->table, 'phone');
    }


    /**
     * @return bool|void
     */
    public function safeDown()
    {
        $this->renameTable($this->table, '{{%user_user}}');
        $this->renameTable('{{%user__access}}', '{{%user_access}}');
        $this->renameTable('{{%user__role}}', '{{%user_role}}');
        $this->renameTable('{{%user__token}}', '{{%user_token}}');
    }
}