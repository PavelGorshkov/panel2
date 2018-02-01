<?php

namespace app\modules\core\components;

use Adldap\Adldap;
use Adldap\Connections\Provider;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class AdLdapComponent
 * @package app\modules\core\components
 */
class AdLdapComponent extends Component
{
    const PROVIDER_NAME = 'default';


    /**
     * @var array
     */
    public $options = [];


    /**
     * @var Adldap
     */
    protected $ad = null;


    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->ad === null) {

            $this->ad = new Adldap();
        }

        parent::init();
    }


    /**
     * @param string $provider
     * @param array $options
     * @throws InvalidConfigException
     * @throws \Adldap\AdldapException
     */
    public function addProvider($provider, array $options)
    {
        $providers = $this->ad->getProviders();

        if (!isset($providers[$provider]) || empty($options)) {

            throw new InvalidConfigException("Provider \"$provider\" already exists!");
        }

        $this->setProvider($provider);
    }


    /**
     * @param $provider
     * @return \Adldap\Connections\ProviderInterface|mixed
     * @throws InvalidConfigException
     * @throws \Adldap\AdldapException
     */
    public function getProvider($provider)
    {
        if (!isset($this->options[$provider])) {

            throw new InvalidConfigException("Adldap Provider \"$provider\" not found");
        }

        $providers = $this->ad->getProviders();

        if (!isset($providers[$provider])) $this->setProvider($provider);

        return $this->ad->getProvider($provider);
    }


    /**
     * @param $provider
     * @param null $options
     * @throws \Adldap\AdldapException
     */
    protected function setProvider($provider, $options = null)
    {

        if ($options === null) {

            $options = $this->options[$provider];
        }

        $config = new Provider($options);

        $this->ad->addProvider($config, $provider);

        if (isset($options['schema']) && is_object($options['schema'])) {

            $this->ad->getProvider($provider)->setSchema($options['schema']);
        }

        $this->ad->connect($provider);
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
     * Convert time
     * @param string $time
     * @param string $format
     * @return null|string
     */
    public function convertTime($time, $format = 'Y-m-d H:i:s')
    {
        // http://stackoverflow.com/questions/10411954/convert-windows-timestamp-to-date-using-php-on-a-linux-box
        return ($time > 0 && $time != '9223372036854775807')
            ? date($format, $time / 10000000 - 11644473600)
            : null;
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