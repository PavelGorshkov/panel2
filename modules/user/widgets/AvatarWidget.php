<?php
namespace app\modules\user\widgets;

use app\modules\core\widgets\Widget;
use app\modules\user\assets\AvatarWidgetAssets;
use app\modules\user\models\Profile;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class AvatarWidget extends Widget {

    /**
     * @var string
     */
    public $width = '100px';
    /**
     * Для достаточно указать только $width
     * @var string
     */
    public $height;
    /**
     * Цвет рамки
     * @var string
     */
    public $backgroundColor = '#000';
    /**
     * URL изображения, помещаемого в рамку
     * @var string
     */
    public $imageSrc;
    /**
     * Альтернативный текст изображения
     * @var string
     */
    public $imageAlt = '';
    /**
     * Если истина - предотвращает кэширование добавлением значения microtime()
     * к src изображения
     * @var boolean
     */
    public $noCache = false;

    /**
     * @var array
     */
    public $imageOptions = [];

    /**
     * Модель пользователя
     * @var Profile
     */
    public $user;

    /**
     * Размер аватарки
     * @var int Пикселей
     */
    public $size = 100;


    /**
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\web\HttpException
     */
    public function run() {

        if ($this->user === null) $this->user = user()->profile;

        $this->imageSrc = $this->user->getAvatarSrc($this->size);

        if ($this->noCache) {

            $this->imageSrc .= '?'.microtime(true);
        }

        $this->imageAlt = empty($this->imageAlt)?$this->user->full_name:$this->imageAlt;
        $this->width = $this->size.'px';
        $this->options = ['class' => 'avatar avatar-'.$this->user->user_id];

        if (!$this->height) {
            $this->height = $this->width;
        }

        ArrayHelper::merge($this->imageOptions, ['alt'=>$this->imageAlt]);

        AvatarWidgetAssets::register($this->view);

        $options = [
            'class' => 'img-wrapper-tocenter',
            'style' => 'width: '.$this->width.'; height: '.$this->height.'; background-color: '.$this->backgroundColor.';',
        ];

        Html::addCssClass($this->options, $options['class']);

        Html::addCssStyle($this->options, $options['style']);

        return Html::tag('div', Html::img(Url::to($this->imageSrc), $this->imageOptions),$this->options);
    }
}