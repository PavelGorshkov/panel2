<?php

namespace app\modules\core\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Theme as YiiTheme;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * Class Theme
 * @package app\modules\core\components
 */
class Theme extends YiiTheme
{
    public $active;

    /**
     * @inheritdoc
     * @param string $path
     * @return string
     */
    public function applyTo($path)
    {
        $pathMap = ArrayHelper::getValue($this->pathMap,$this->active,$this->pathMap);

        if (empty($pathMap)) {

            if (($basePath = $this->getBasePath()) === null) {

                throw new InvalidConfigException('The "basePath" property must be set.');
            }

            $pathMap = [Yii::$app->getBasePath() => [$basePath]];
        }


        $path = FileHelper::normalizePath($path);

        foreach ($pathMap as $from => $tos) {

            $from = FileHelper::normalizePath(Yii::getAlias($from)) . DIRECTORY_SEPARATOR;

            if (strpos($path, $from) === 0) {

                $n = strlen($from);

                foreach ((array) $tos as $to) {

                    $to = FileHelper::normalizePath(Yii::getAlias($to)) . DIRECTORY_SEPARATOR;
                    $file = $to . substr($path, $n);

                    if (is_file($file)) {
                        return $file;
                    }
                }
            }
        }

        return $path;
    }
}