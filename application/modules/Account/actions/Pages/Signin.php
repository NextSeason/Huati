<?php

Class SigninAction extends \Yaf\Action_Abstract {

    private $request;
    private $params;

    private $controller;
    private $session;

    private $accountModel;

    public function execute() {
        $this->display( 'account/signin' );
    }
}
