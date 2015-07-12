<?php

Class PasswdAction extends \Local\BaseAction {
    private $data = [];

    public function __execute() {
        $this->tpl = 'settings/passwd';

        if( !$this->account ) {
            \Local\Utils::redirect( '/signin' );
        }

        $this->data['page'] = 'passwd';

        return $this->data;
    }
}