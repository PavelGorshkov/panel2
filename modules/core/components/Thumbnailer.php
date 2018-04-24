<?php
namespace app\modules\core\components;

use app\modules\core\helpers\File;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\base\Component;
use yii\web\ServerErrorHttpException;

/**
 * Компонент по работе с превьшками изображений
 *
 * Class Thumbnailer
 * @package app\modules\core\components
 */
class Thumbnailer extends Component{

    /**
     * @var array
     */
    public $options = [];

    /**
     * @var string
     */
    public $thumbDir = 'thumbs';


    /**
     * @param string $file Полный путь к исходному файлу в файловой системе
     * @param string $uploadDir Подпапка в папке с миниатюрами куда надо поместить изображение
     * @param float $width Ширина изображения. Если не указана - будет вычислена из высоты
     * @param float $height Высота изображения. Если не указана - будет вычислена из ширины
     * @param boolean $crop Обрезка миниатюры по размеру
     * @param bool $replace
     * 
     * @return string
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     */
    public function thumbnail(
        $file,
        $uploadDir,
        $width = 0.0,
        $height = 0.0,
        $crop = true,
        $replace = false
    ) {
        if (!$width && !$height) {
            throw new ServerErrorHttpException("Incorrect width/height");
        }

        $filename = explode('.', $file);

        if (count($filename)>1) {

            $ext = array_pop($filename);

            $name = implode('.', $filename) . '_' . $width .'x' . $height . '.'. $ext;

        } else {

            $name = $width . 'x' . $height . '_' . $file;
        }

        $uploadDir = Yii::getAlias($uploadDir);

        File::checkPath($uploadDir . $this->thumbDir);
        $thumbFile = $uploadDir . $this->thumbDir. DIRECTORY_SEPARATOR . $name;

        $thumbMode = $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET;

        if (!file_exists($thumbFile) || $replace) {

            if (false === File::checkPath($uploadDir)) {
                throw new ServerErrorHttpException('Директория "'.$uploadDir.'не доступна для записи!');
            }

            $img = Imagine::getImagine()->open($uploadDir . $file);

            $originalWidth = $img->getSize()->getWidth();
            $originalHeight = $img->getSize()->getHeight();

            if (!$width) {
                $width = $height / $originalHeight * $originalWidth;
            }

            if (!$height) {
                $height = $width / $originalWidth * $originalHeight;
            }

            File::checkPath(dirname($thumbFile));

            $img->thumbnail(new Box($width, $height), $thumbMode)->save($thumbFile, $this->options);
        }

        return $this->path2Url($thumbFile);
    }


    /**
     * @param string $path
     * @param bool $is_full
     * @return string
     */
    protected function path2Url($path, $is_full = false) {

        $base_path = realpath(app()->basePath).'/web';

        if ($is_full) {

            return app()->request->absoluteUrl. str_replace($base_path, '', $path);
        } else {
            return app()->request->baseUrl . str_replace($base_path, '', $path);
        }
    }


}