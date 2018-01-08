<?php

namespace app\modules\core\components;

use Imagine\Gd\Imagine as GdImagine;
use Imagine\Gmagick\Imagine as GmagickImagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine as ImagickImagine;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;

/**
 * Class Imagine
 * @package app\modules\core\components
 */
class Imagine extends BaseObject
{

    /**
     * GD2 driver definition for Imagine implementation using the GD library.
     */
    const DRIVER_GD2 = 'gd2';


    /**
     * gmagick driver definition.
     */
    const DRIVER_GMAGICK = 'gmagick';


    /**
     * imagick driver definition.
     */
    const DRIVER_IMAGICK = 'imagick';


    /**
     * @var ImagineInterface instance.
     */
    private static $_imagine;

    /**
     * @var array|string the driver to use. This can be either a single driver name or an array of driver names.
     * If the latter, the first available driver will be used.
     */
    public static $driver = [self::DRIVER_GMAGICK, self::DRIVER_IMAGICK, self::DRIVER_GD2];


    /**
     * @return GdImagine|GmagickImagine|ImagickImagine
     *
     * @throws ServerErrorHttpException
     */
    protected static function createImagine()
    {
        foreach ((array)static::$driver as $driver) {

            switch ($driver) {

                case self::DRIVER_GMAGICK:
                    if (class_exists('Gmagick', false)) {
                        return new GmagickImagine();
                    }
                    break;
                case self::DRIVER_IMAGICK:
                    if (class_exists('Imagick', false)) {
                        return new ImagickImagine();
                    }
                    break;
                case self::DRIVER_GD2:
                    if (function_exists('gd_info')) {
                        return new GdImagine();
                    }
                    break;
                default:
                    throw new ServerErrorHttpException("Unknown driver: $driver");
            }
        }
        throw new ServerErrorHttpException(
            "Your system does not support any of these drivers: " . implode(
                ',',
                (array)static::$driver
            )
        );
    }


    /**
     * @return ImagineInterface the `Imagine` object
     * @throws Exception
     */
    public static function getImagine()
    {
        if (self::$_imagine === null) {
            self::$_imagine = static::createImagine();
        }

        return self::$_imagine;
    }


    /**
     * Crops an image.
     *
     * For example,
     *
     * ~~~
     * $obj->crop('path\to\image.jpg', 200, 200, [5, 5]);
     *
     * $point = new \Imagine\Image\Point(5, 5);
     * $obj->crop('path\to\image.jpg', 200, 200, $point);
     * ~~~
     * @param string $filename the image file path or path alias.
     * @param integer $width the crop width
     * @param integer $height the crop height
     * @param array $start the starting point. This must be an array with two elements representing `x` and `y` coordinates.
     *
     * @return \Imagine\Image\ManipulatorInterface
     * @throws Exception
     */
    public static function crop($filename, $width, $height, array $start = [0, 0])
    {
        if (!isset($start[0], $start[1])) {
            throw new Exception('$start must be an array of two elements.');
        }

        return static::getImagine()
            ->open($filename)
            ->copy()
            ->crop(new Point($start[0], $start[1]), new Box($width, $height));
    }


    /**
     * Обрезка изображения
     *
     * @param string $filename
     * @param int $width
     * @param int $height
     *
     * @return ImageInterface
     * @throws Exception
     */
    public static function resize($filename, $width, $height)
    {
        $image = static::getImagine()->open($filename);

        $realWidth = $image->getSize()->getWidth();
        $realHeight = $image->getSize()->getHeight();

        if ($realWidth > $width || $realHeight > $height) {
            $ratio = $realWidth / $realHeight;

            if ($ratio > 1) {
                $height = $width / $ratio;
            } else {
                $width = $height * $ratio;
            }

            $image->resize(new Box($width, $height));
        }

        return $image;
    }


    /**
     * @param ImagineInterface $imagine the `Imagine` object.
     */
    public static function setImagine($imagine)
    {
        self::$_imagine = $imagine;
    }
}