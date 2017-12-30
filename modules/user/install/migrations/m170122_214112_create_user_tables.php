<?php
namespace app\modules\user\install\migrations;

use app\modules\core\components\Migration;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use yii\db\Expression;

class m170122_214112_create_user_tables extends Migration {

    protected $table = '{{%user_user}}';

    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function safeUp() {

        $this->createTable(
            $this->table,
            [
                'id'=> $this->primaryKey(),
                'username' => $this->string(25)->notNull(),
                'email' => $this->string(150)->notNull(),
                'email_confirm'=>$this->boolean()->notNull()->defaultValue('0'),
                'hash' => $this->string(60)->notNull(),
                'auth_key'=>$this->string(32)->notNull(),
                'user_ip'=>$this->integer()->null(),
                'status'=>$this->boolean()->notNull()->defaultValue('2'),
                'status_change_at'=>$this->dateTime()->null(),
                'visited_at'=>$this->dateTime()->null(),
                'registered_from' => $this->boolean()->notNull()->defaultValue(0),
                'access_level'=>$this->boolean()->notNull()->defaultValue('0'),
                'logged_in_from'=> $this->boolean()->null(),
                'logged_at'=> $this->integer()->null(),
                'full_name' => $this->string(150)->notNull(),
                'avatar' => $this->string(150)->null(),
                'about' => $this->text()->null(),
                'phone' => $this->char(30)->null(),
            ],
            $this->getOptions('InnoDB')
        );

        $this->createIndex("ux_{$this->tableName}_username", $this->table, "username", true);
        $this->createIndex("ux_{$this->tableName}_email", $this->table, "email", true);
        $this->createIndex("ix_{$this->tableName}_status", $this->table, "status");

        $this->createDateColumns();

       $this->insert($this->table, [
            'id'=>1,
            'username'=> 'admin',
            'email'=> 'webmaster@marsu.ru',
            'email_confirm'=> 1,
            'hash'=> Password::hash('ifynmtylhfyfn'),
            'auth_key'=> app()->security->generateRandomKey(),
            'status'=>UserStatusHelper::STATUS_ACTIVE,
            'status_change_at'=>new Expression('NOW()'),
            'registered_from'=>RegisterFromHelper::FORM,
            'access_level'=> UserAccessLevelHelper::LEVEL_ADMIN,
            'full_name'=> 'Администратор',
            'about'=> 'Системный администратор',
        ]);
    }


    public function safeDown() {

        $this->dropTable('{{%user_profile}}');
        $this->dropTable($this->table);
    }
}