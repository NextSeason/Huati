<?php

Class SigninAction extends \Local\MisAction {
    public function __execute() {
        $data = array();

        $this->tpl = 'mis/signin';

        return $data;
    }
}
