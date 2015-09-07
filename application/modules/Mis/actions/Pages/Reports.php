<?php

Class ReportsAction extends \Local\MisAction {

    private $data = [];

    public function __execute() {
        $this->tpl = 'mis/reports';

        $this->paramsProcessing()->getReports()->getPosts()->getAccounts();

        return $this->data;
    }

    public function __mobile() {
        return $this->__execute();
    }

    private function getAccounts() {
        foreach( $this->data['reports'] as &$report ) {
            $account = \Accounts\Api::get( $report['post_id'] );
            $report['account'] = $account;
        }
        return $this;
    }

    private function getPosts() {
        foreach( $this->data['reports'] as &$report ) {
            $post = \Posts\Api::get( $report['post_id'] );

            if( $post ) {
                $post['data'] = \Posts\Api::getData( $report['post_id'] );
            }
            $report['post'] = $post;
        }
        return $this;
    }

    private function getReports() {
        $reportModel = new ReportModel();

        $reports = $reportModel->select( [
            'where' => [
                [ 'status', $this->params['status'] ]
            ],
            'order' => [
                [ 'id', 'DESC' ]
            ],
            'rn' => 30
        ] );

        $this->data['reports'] = $reports;
        return $this;
    }

    private function paramsProcessing() {
        $status = $this->__getQuery( 'status' );

        if( is_null( $status ) ) {
            $status = 0;
        }

        $this->params = [
            'status' => $status
        ];

        return $this;
    }
}
