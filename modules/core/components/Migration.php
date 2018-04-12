<?php
namespace app\modules\core\components;

use yii\db\Exception;

/**
 * Class Migration
 * @package app\modules\core\components
 *
 * @property string $tableName - table without prefix
 */
class Migration extends \yii\db\Migration
{
    protected $restrict = 'RESTRICT';

    protected $cascade = 'CASCADE';

    protected $dbType;

    protected $table;

    /**
     * @param string|null $table
     * @return mixed
     */
    protected function getTableName($table = null)
    {
        if ($table === null) $table = $this->table;

        return str_replace(['{', '}', '%'], '', $table);
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        switch ($this->db->driverName) {
            case 'mysql':

                $this->dbType = 'mysql';
                break;

            case 'pgsql':
                $this->dbType = 'pgsql';
                break;

            case 'dblib':
            case 'mssql':
            case 'sqlsrv':

                $this->restrict = 'NO ACTION';
                $this->dbType = 'sqlsrv';
                break;

            default:
                throw new \RuntimeException('Your database is not supported!');
        }
    }


    /**
     * get options for schema
     *
     * @param string $type
     * @return string options
     */
    public function getOptions($type = 'MyISAM')
    {
        switch ($this->db->driverName) {

            case 'mysql':

                return 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=' . $type;

            default:
                return null;
        }
    }


    /**
     * Создание колонок дат (создание и обновление) в таблице БД
     * @param string $table
     */
    protected function createDateColumns($table = null)
    {
        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createDateColumns'));
        }

        $this->addColumn($table, 'created_at', $this->dateTime()->defaultExpression('NOW()'));
        $this->addColumn($table, 'updated_at', $this->timestamp());
    }


    /**
     * Создание колонок пользователей (кто создал и изменил) в таблице БД
     * @param null $table
     */
    protected function createUserColumns($table = null)
    {
        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createUserColumns'));
        }

        $this->addColumn($table, 'created_by', $this->integer()->null());
        $this->addColumn($table, 'updated_by', $this->integer()->null());
    }
}