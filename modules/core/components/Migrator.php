<?php

namespace app\modules\core\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Компонент по управлению миграциями приложения через web
 *
 * Class Migrator
 * @package app\modules\core\components
 */
class Migrator extends Component
{
    public $migrationTable = '{{%migration}}';

    /**
     * Проверяем на незавершённые миграции:
     *
     * @param string $module - required module
     * @param bool $class - migration class
     *
     * @return bool is updated to migration
     *
     * @throws Exception
     */
    public function checkForBadMigration($module, $class = false)
    {
        echo 'Проверяем на наличие незавершённых миграций.<br />';

        $data = (new Query())
            ->select(['version', 'apply_time'])
            ->distinct()
            ->from($this->migrationTable)
            ->orderBy(['id' => SORT_DESC])
            ->where('module = :module', [':module' => $module])
            ->all();

        if (($data !== []) || ((strpos($class, '_base') !== false) && ($data[] = [
                    'version' => $class,
                    'apply_time' => 0,
                ]))
        ) {
            foreach ($data as $migration) {

                if ($migration['apply_time'] == 0) {

                    echo 'Откат миграции ' . $migration['version'] . ' для модуля ' . $module . '.<br />';
                    Yii::trace('Откат миграции ' . $migration['version'] . ' для модуля ' . $module . '.', __METHOD__);

                    if ($this->migrateDown($module, $migration['version']) !== false) {

                        (new Migration())->delete(
                            $this->migrationTable,
                            'version = :version AND module=:module',
                            [':version' => $migration['version'], ':module' => $module]);

                    } else {

                        Yii::warning('Не удалось выполнить откат миграции ' . $migration['version'] . ' для модуля ' . $module . '.', __METHOD__);
                        echo 'Не удалось выполнить откат миграции ' . $migration['version'] . ' для модуля ' . $module . '.<br />';

                        return false;
                    }
                }
            }
        } else {
            Yii::trace('Для модуля ' . $module . ' не требуется откат миграции.', __METHOD__);
            echo 'Для модуля ' . $module . ' не требуется откат миграции.<br />';
        }

        return true;
    }


    /**
     * Создает таблицу историй миграций
     */
    protected function createMigrationHistoryTable()
    {
        Yii::trace("Создаем таблицу для хранения версий миграций " . __METHOD__);

        echo "Создаем таблицу для хранения версий миграций " . $this->migrationTable;

        $migration = new Migration();

        $migration->createTable(
            $this->migrationTable,
            [
                'id' => $migration->primaryKey(),
                'module' => $migration->string()->notNull(),
                'version' => $migration->string()->notNull(),
                'apply_time' => $migration->integer()->defaultValue('0')
            ],
            $migration->getOptions()
        );

        $migration->createIndex(
            'ix_migrations_module',
            $this->migrationTable,
            "module",
            false
        );
    }


    /**
     * Получение истории миграции для конкретного модуля
     *
     * @param string $module
     * @param int $limit
     * @return array
     */
    public function getMigrationHistory($module, $limit = 20)
    {
        $data = (new Query())
            ->select(['version', 'apply_time'])
            ->from($this->migrationTable)
            ->orderBy(['id' => SORT_DESC])
            ->where('module = :module', [':module' => $module])
            ->limit($limit)
            ->all();

        return ArrayHelper::map($data, 'version', 'apply_time');
    }


    /**
     * Получение списка новых миграциий для модуля приложения
     *
     * @param string $module
     * @return array
     */
    protected function getNewMigrations($module)
    {
        $applied = [];

        foreach ($this->getMigrationHistory($module, -1) as $version => $time) {
            if ($time) {
                $applied[substr($version, 1, 13)] = true;
            }
        }

        $migrations = [];

        if (($migrationsPath = self::getPathMigration($module)) && is_dir(
                $migrationsPath
            )
        ) {

            $handle = opendir($migrationsPath);

            while (($file = readdir($handle)) !== false) {

                if ($file === '.' || $file === '..') continue;

                $path = $migrationsPath . '/' . $file;

                if (
                    preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches)
                    && is_file($path)
                    && !isset($applied[$matches[2]])
                ) {
                    $migrations[] = $matches[1];
                }
            }

            closedir($handle);
            sort($migrations);
        }

        return $migrations;
    }


    /**
     * Получение пути к папке миграций модуля
     *
     * @param string $module
     *
     * @return string
     */
    public static function getPathMigration($module)
    {

        return Yii::getAlias(sprintf("@app/modules/%s/install/migrations", $module));
    }


    /**
     * @inheritdoc
     */
    public function init()
    {

        if (app()->db->schema->getTableSchema($this->migrationTable) === null) {

            $this->createMigrationHistoryTable();
        }

        parent::init();
    }


    /**
     * Check each modules for new migrations
     *
     * @param string $module - required module
     * @param string $className - class of migration
     *
     * @return mixed version and apply time
     */
    protected function instantiateMigration($module, $className)
    {
//        $file = (string) self::getPathMigration($module).DIRECTORY_SEPARATOR.$class.'.php';
//        require_once $file;

        $namespace = '\\app\\modules\\' . $module . '\\install\\migrations\\';

        $class = $namespace . $className;

        return new $class;
    }


    /**
     * Отмена миграции:
     *
     * @param string $module
     * @param string $class
     *
     * @return bool
     * @throws Exception
     */
    public function migrateDown($module, $class)
    {
        Yii::trace('Отменяем миграцию ' . $class, __METHOD__);

        $start = microtime(true);
        $migration = $this->instantiateMigration($module, $class);

        if (!($migration instanceof Migration)) return true;

        ob_start();
        ob_implicit_flush(false);

        $result = $migration->down();

        Yii::trace($msg = ob_get_clean());

        app()->cache->delete('getMigrationHistory');

        if ($result !== false) {

            (new Migration())->delete(
                $this->migrationTable,
                "version = :ver AND module = :module",
                [':ver' => $class, ':module' => $module]
            );

            $time = microtime(true) - $start;
            Yii::trace('Миграция ' . $class . ' отменена за ' . sprintf("%.3f", $time) . ' сек.', __METHOD__);

            return true;

        } else {

            $time = microtime(true) - $start;

            Yii::error('Ошибка отмены миграции ' . $class . ' (' . sprintf("%.3f", $time) . ' сек.)', __METHOD__);

            throw new Exception('Во время установки возникла ошибка: ' . $msg);
        }
    }


    /**
     * Применение миграции
     *
     * @param string $module
     * @param string $className
     *
     * @return void
     * @throws Exception
     */
    protected function migrateUp($module, $className)
    {
        $start = microtime(true);
        $migration = $this->instantiateMigration($module, $className);

        if (!($migration instanceof Migration)) return;

        ob_start();
        ob_implicit_flush(false);

        echo 'Применяем миграцию ' . $className . '<br />';

        // Вставляем запись о начале миграции
        /* @var Migration $className */
        (new Migration())->insert(
            $this->migrationTable,
            [
                'version' => $className,
                'module' => $module,
                'apply_time' => 0,
            ]
        );

        $result = $migration->up();

        Yii::trace($msg = ob_get_clean());

        if ($result !== false) {

            // Проставляем "установлено"
            (new Migration())->update(
                $this->migrationTable,
                ['apply_time' => time()],
                "version = :ver AND module = :mod", [
                    ':ver' => $className,
                    ':mod' => $module,
                ]
            );

            $time = microtime(true) - $start;

            Yii::trace("Миграция " . $className . " применена за " . sprintf("%.3f", $time) . " сек.", __METHOD__);
            echo "Миграция " . $className . " применена за " . sprintf("%.3f", $time) . " сек...<br />";

        } else {

            $time = microtime(true) - $start;

            Yii::error('Ошибка применения миграции ' . $className . ' (' . sprintf("%.3f", $time) . ' сек.)', __METHOD__);

            throw new Exception('Во время установки возникла ошибка: ' . $msg);
        }
    }


    /**
     * Обновление миграций модуля
     *
     * @param $module
     * @return bool
     * @throws Exception
     */
    public function updateToLatestModule($module)
    {
        if (($newMigrations = $this->getNewMigrations($module)) !== []) {

            Yii::trace("Обновляем до последней версии базы модуль " . $module, __METHOD__);
            echo "Обновляем до последней версии базы модуль " . $module . '<br />';

            foreach ($newMigrations as $migration) {

                if ($this->migrateUp($module, $migration) === false) {

                    return false;
                }
            }
        }

        return true;
    }


    /**
     * Обновление миграций системных модулей (core и user)
     * @throws Exception
     */
    public function updateToLatestSystem()
    {
        foreach (['core', 'user'] as $module) {

            $this->updateToLatestModule($module);
        }
    }
}