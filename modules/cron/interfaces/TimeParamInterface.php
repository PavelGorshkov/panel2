<?php

namespace app\modules\cron\interfaces;

/**
 * Interface TimeParamInterface
 * @package app\modules\cron\interfaces
 */
interface TimeParamInterface {

    /**
     * @param string $attr
     * @return array
     */
    public function getTimeData($attr);
}
