<?php
namespace app\modules\core\widgets;
use yii\web\ServerErrorHttpException;

/**
 * Class CalloutWidget
 * @package app\modules\core\widgets
 */
class CalloutWidget extends Widget
{
    public $type;

    public $title = null;

    public $message = '';

    protected $types = [
        'error'   => 'danger',
        'info'    => 'info',
        'success' => 'success',
        'warning' => 'warning'
    ];


    /**
     * @throws ServerErrorHttpException
     */
    public function init() {

        if (
            $this->type === null
         || empty($this->types[$this->type])
        ) {

            $this->type = 'info';
        }


        if ($this->message === null) {

            throw new ServerErrorHttpException('Collout message is empty!');
        }
    }


    /**
     * @return string
     */
    public function run() {

        $text = '<div class="callout callout-'.$this->type.'">';

        if (!empty($this->title)) {

            $text .= '<h4>'.$this->title.'</h4>';
        }

        $text .= '<p>'.$this->message.'</p></div>';

        return $text;
    }
}