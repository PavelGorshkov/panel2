<?php
namespace app\modules\core\widgets;

class BoxSolidWidget extends BoxWidget {

    public function init() {

        $this->type = 'solid';

        $this->isTile = false;

        $this->withBorder = true;

        parent::init();
    }



}