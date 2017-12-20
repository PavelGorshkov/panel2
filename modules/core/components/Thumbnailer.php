<?php
namespace app\modules\core\components;

use app\modules\core\helpers\File;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\base\Component;
use yii\web\HttpException;

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
     *
     * @return string
     * @throws HttpException
     */
    public function thumbnail(
        $file,
        $uploadDir,
        $width = 0,
        $height = 0,
        $crop = true
    ) {
        if (!$width && !$height) {
            throw new HttpException(500, "Incorrect width/height");
        }

        $name = $width . 'x' . $height . '_' . basename($file);

        File::checkPath($uploadDir . $this->thumbDir);
        $thumbFile = $uploadDir . $this->thumbDir. DIRECTORY_SEPARATOR . $name;

        $thumbMode = $crop ? ImageInterface::THUMBNAIL_OUTBOUND : ImageInterface::THUMBNAIL_INSET;

        if (!file_exists($thumbFile)) {

            if (false === File::checkPath($uploadDir)) {
                throw new HttpException(500, 'Директория "'.$uploadDir.'не доступна для записи!');
            }

            $img = Imagine::getImagine()->open($file);

            $originalWidth = $img->getSize()->getWidth();
            $originalHeight = $img->getSize()->getHeight();

            if (!$width) {
                $width = $height / $originalHeight * $originalWidth;
            }

            if (!$height) {
                $height = $width / $originalWidth * $originalHeight;
            }

            $img->thumbnail(new Box($width, $height), $thumbMode)->save($thumbFile, $this->options);
        }

        return $this->path2Url($thumbFile);
    }


    protected function path2Url($path) {

        $base_path = realpath(app()->basePath).'/web';

        return str_replace($base_path, '', $path);
    }
}