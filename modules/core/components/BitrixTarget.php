<?php
namespace app\modules\core\components;

use linslin\yii2\curl\Curl;
use yii\base\InvalidConfigException;
use yii\log\Logger;
use yii\log\Target;

/**
 * Class BitrixTarget
 * @package app\modules\core\components
 */
class BitrixTarget extends Target
{
    const CATEGORY = 'bitrix';

    public $url;

    public $debug = false;

    public $categories = [
        self::CATEGORY
    ];

    public $levels = [
        'info',
        'error'
    ];

    /**
     * @var Curl|null
     */
    protected $curl = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->url)) {

            throw new InvalidConfigException('The users_id must be array and nor empty');
        }

        $this->logVars = [];
        $this->categories = [self::CATEGORY];
        $this->levels = ['info','error'];
    }


    /**
     * @param array $message
     * @return string
     */
    public function transformMessage($message)
    {
        if ($message[1] === Logger::LEVEL_ERROR) {

            return $this->formatMessage($message);
        } else {

            return $message[0];
        }

    }

    /**
     * Exports log [[messages]] to a specific destination.
     * Child classes must implement this method.
     */
    public function export()
    {
        if ($this->curl === null) {

            $this->curl = new Curl();
        }

        $text = 'Ресурс "'.app()->name.'" ('.app()->request->absoluteUrl.') отправил сообщение:'."\n";

        $text .= implode("\n", array_map([$this, 'transformMessage'], $this->messages)) . "\n";

        $url = strpos('?', $this->url)===false?$this->url.'?'.uniqid():$this->url.'&'.uniqid();

        $data = $this->curl->reset()->setOption(
            CURLOPT_POSTFIELDS,
            http_build_query(array(
                    'message'=>$text,
                )
            ))->post($url);

        if ($this->debug) {

            $data .= $url;

            file_put_contents(
                \Yii::getAlias('@app/runtime/logs').'/bitrix.log',
                print_r($data, 1),
                FILE_APPEND
            );
        }
    }
}