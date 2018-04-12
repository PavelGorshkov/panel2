<?php
namespace app\modules\core\components;

use app\modules\core\helpers\LoggerTrait;
use app\modules\core\helpers\OutputMessageListHelper;
use app\modules\core\helpers\OutputMessageTrait;
use app\modules\core\interfaces\LoggerInterface;
use app\modules\core\interfaces\OutputMessageInterface;
use yii\base\Component;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\log\Logger;

/**
 * Компонент по управлению миграциями приложения через web
 *
 * Class Migrator
 * @package app\modules\core\components
 */
class Migrator extends Component implements OutputMessageInterface, LoggerInterface
{
    const CATEGORY_LOG = 'migration';

    use OutputMessageTrait;
    use LoggerTrait;

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
        $this->message(
            'Проверяем на наличие незавершённых миграций.',
            OutputMessageListHelper::INFO
        );

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

                    $this->message(
                        'Откат миграции ' . $migration['version'] . ' для модуля ' . $module . '.',
                        OutputMessageListHelper::WARNING
                    );

                    if ($this->migrateDown($module, $migration['version']) !== false) {

                        (new Migration())->delete(
                            $this->migrationTable,
                            'version = :version AND module=:module',
                            [':version' => $migration['version'], ':module' => $module]);

                        $this->message(
                            'Выполнено.',
                            OutputMessageListHelper::SUCCESS
                        );

                    } else {

                        \Yii::warning('Не удалось выполнить откат миграции ' . $migration['version'] . ' для модуля ' . $module . '.', __METHOD__);

                        return false;
                    }
                }
            }
        } else {
            $this->message(
                'Для модуля ' . $module . ' не требуется откат миграции.',
                OutputMessageListHelper::INFO
            );
        }

        return true;
    }


    /**
     * Создает таблицу историй миграций
     * @throws \yii\base\InvalidConfigException
     */
    protected function createMigrationHistoryTable()
    {
        $this->message(
            "Создаем таблицу для хранения версий миграций \"{$this->migrationTable}\"",
            OutputMessageListHelper::WARNING
        );

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

        $this->message(
            'Выполнено',
            OutputMessageListHelper::SUCCESS
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

        return \Yii::getAlias(sprintf("@app/modules/%s/install/migrations", $module));
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initLogger();

        if (app()->db->schema->getTableSchema($this->migrationTable, true) === null) {

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
        $namespace = '\\app\\modules\\' . $module . '\\install\\migrations\\';

        $class = $namespace . $className;

        return new $class;
    }


    /**
     * @param string $message
     * @param int $type
     * @throws \yii\base\InvalidConfigException
     */
    protected function message($message, $type = null)
    {

        $this->addMessage($message, $type);

        if ($type === OutputMessageListHelper::ERROR) {

            $this->addLog(Logger::LEVEL_ERROR, $message, self::CATEGORY_LOG);
        } else {

            $this->addLog(Logger::LEVEL_INFO, $message, self::CATEGORY_LOG);
        }
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
        $this->message('Отменяем миграцию ' . $class, OutputMessageListHelper::INFO);

        $start = microtime(true);
        $migration = $this->instantiateMigration($module, $class);

        if (!($migration instanceof Migration)) return true;

        ob_start();
        ob_implicit_flush(false);

        $result = $migration->down();

        $this->message($msg = ob_get_clean());

        app()->cache->delete('getMigrationHistory');

        if ($result !== false) {

            ob_start();
            ob_implicit_flush(false);

            (new Migration())->delete(
                $this->migrationTable,
                "version = :ver AND module = :module",
                [':ver' => $class, ':module' => $module]
            );

            $this->message($msg = ob_get_clean());

            $time = microtime(true) - $start;
            $this->message('Миграция ' . $class . ' отменена за ' . sprintf("%.3f", $time) . ' сек.', OutputMessageListHelper::ERROR);

            return true;

        } else {

            $time = microtime(true) - $start;

            $this->message('Ошибка отмены миграции ' . $class . ' (' . sprintf("%.3f", $time) . ' сек.)', OutputMessageListHelper::ERROR);

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

        $this->message(
            'Применяем миграцию ' . $className,
            OutputMessageListHelper::WARNING
        );

        ob_start();
        ob_implicit_flush(false);

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

        $this->message($msg = ob_get_clean());

        $time = microtime(true) - $start;

        if ($result !== false) {

            ob_start();
            ob_implicit_flush(false);
            // Проставляем "установлено"
            (new Migration())->update(
                $this->migrationTable,
                ['apply_time' => time()],
                "version = :ver AND module = :mod", [
                    ':ver' => $className,
                    ':mod' => $module,
                ]
            );

            $this->message($msg = ob_get_clean());


            $this->message(
                "Миграция \"{$className}\" применена за " . sprintf("%.3f", $time) . "сек...",
                OutputMessageListHelper::SUCCESS
            );

        } else {

            $this->message(
                "Во время установки возникла ошибка: {$msg}",
                OutputMessageListHelper::ERROR
            );
            ob_start();
            ob_implicit_flush(false);

            (new Migration())->delete($this->migrationTable, [
                'version' => $className,
                'module' => $module,
                'apply_time' => 0,
            ]);


            throw new Exception(ob_get_clean());
        }
    }


    /**
     * Set configTargets
     * @return mixed
     */
    public function setTargets()
    {

        return [
            [
                'class' => 'yii\log\FileTarget',
                'categories' => [self::CATEGORY_LOG],
                'logFile' => '@runtime/logs/migrations.log',
                'logVars' => [],
            ]
        ];
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
        $this->checkForBadMigration($module);

        $this->message(
            "Обновляем до последней версии базы модуль \"$module\"",
            OutputMessageListHelper::WARNING
        );

        if (($newMigrations = $this->getNewMigrations($module)) !== []) {

            foreach ($newMigrations as $migration) {

                if ($this->migrateUp($module, $migration) === false) {

                    return false;
                }
            }
        }

        $this->message(
            "Модуль \"{$module}\" обновлен!",
            OutputMessageListHelper::SUCCESS
        );

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