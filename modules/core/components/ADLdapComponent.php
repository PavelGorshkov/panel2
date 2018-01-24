<?php

namespace app\modules\core\components;

use Adldap\Adldap;
use Adldap\Connections\Provider;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class ADLdapComponent
 * @package app\modules\core\components
 */
class ADLdapComponent extends Component
{
    const PROVIDER_NAME = 'default';


    /**
     * @var array
     */
    public $options = [];


    /**
     * @var Adldap
     */
    protected $ad;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        try {

            $this->ad = new Adldap();

            foreach ($this->options as $provider => $options) {

                $this->ad->addProvider($provider, new Provider($options));
                $this->ad->connect($provider);
            }

        } catch (\Exception $e) {

            throw new InvalidConfigException($e->getMessage());
        }
    }


    /**
     * @return Adldap
     */
    public function getAdLdap()
    {
        return $this->ad;
    }


    /**
     * @param Adldap $adLdap
     */
    public function setAdLdap(Adldap $adLdap)
    {
        $this->ad = $adLdap;
    }


    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (is_callable([$this->ad, $method])) {

            return call_user_func_array([$this->ad, $method], $params);
        } else {

            return parent::__call($method, $params);
        }
    }


    /**
     * Convert time
     * @param string $time
     * @param string $format
     * @return null|string
     */
    public function convertTime($time, $format = 'Y-m-d H:i:s')
    {
        // http://stackoverflow.com/questions/10411954/convert-windows-timestamp-to-date-using-php-on-a-linux-box
        return ($time > 0 && $time != '9223372036854775807')
            ?date($format, $time / 10000000 - 11644473600)
            :null;
    }


    /**
     * Convert generalized time string
     * @param string $gtime
     * @param string $format
     *
     * @return string
     */
    public function convertGTime($gtime, $format = 'Y-m-d H:i:s')
    {
        // http://stackoverflow.com/questions/26981144/active-direcory-whencreated-to-mysql-datetime
        if (preg_match('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2}).+/', $gtime, $matches)) {

            $time = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
            return date($format, $time);
        }
        return null;
    }
}