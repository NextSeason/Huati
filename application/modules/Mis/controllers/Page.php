<?php

use Yaf\Registry;

Class PageController extends BasePageController {
    public function init() {
        parent::init();

        $this->actions = array(
            'signin' => $this->action_path . 'Signin.php'
        );
    }
}
