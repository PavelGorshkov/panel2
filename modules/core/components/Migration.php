<?php

namespace app\modules\core\components;


use yii\db\Exception;

/**
 * Class Migration
 * @package app\modules\core\components
 *
 * @property string $tableName - table without prefix
 */
class Migration extends \yii\db\Migration {

    protected $restrict = 'RESTRICT';

    protected $cascade = 'CASCADE';

    protected $dbType;

    protected $table;

    protected function gettableName($table = null) {

        if ($table === null) $table = $this->table;

        return str_replace(['{', '}', '%'], '', $table);
    }

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
     * @return string options
     */
    public function getOptions($type = 'MyISAM')
    {
        switch ($this->db->driverName) {

            case 'mysql':

                return 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE='.$type;

            default:
                return null;
        }
    }


    protected function createDateColumns($table = null) {

        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createDateColumns'));
        }

        $this->addColumn($table, 'created_at', $this->dateTime()->defaultExpression('NOW()'));
        $this->addColumn($table, 'updated_at', $this->timestamp());
    }


    protected function createUserColumns($table = null) {

        if ($table === null && $this->table) {

            $table = $this->table;
        } else {

            new Exception(sprintf('parameter "table" not defined in class "%s" method "%s"', get_called_class(), 'createUserColumns'));
        }

        $this->addColumn($table, 'created_by', $this->integer()->null());
        $this->addColumn($table, 'updated_by', $this->integer()->null());
    }


    public function dropColumnConstraints($table, $column)
    {
        $table = Yii::$app->db->schema->getRawTableName($table);
        $cmd = Yii::$app->db->createCommand('SELECT name FROM sys.default_constraints
                                WHERE parent_object_id = object_id(:table)
                                AND type = \'D\' AND parent_column_id = (
                                    SELECT column_id
                                    FROM sys.columns
                                    WHERE object_id = object_id(:table)
                                    and name = :column
                                )', [ ':table' => $table, ':column' => $column ]);

        $constraints = $cmd->queryAll();
        foreach ($constraints as $c) {
            $this->execute('ALTER TABLE '.Yii::$app->db->quoteTableName($table).' DROP CONSTRAINT '.Yii::$app->db->quoteColumnName($c['name']));
        }
    }
}