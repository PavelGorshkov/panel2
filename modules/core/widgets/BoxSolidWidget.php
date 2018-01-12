<?php
namespace app\modules\core\widgets;

/**
 * Class BoxSolidWidget
 * @package app\modules\core\widgets
 */
class BoxSolidWidget extends BoxWidget {

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function init() {

        $this->type = 'solid';

        $this->isTile = false;

        $this->withBorder = true;

        parent::init();
    }



}