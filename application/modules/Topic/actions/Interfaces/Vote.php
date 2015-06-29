<?php

Class VoteAction extends \Local\BaseAction {
    private $data = array();
    private $targetPostData = null;

    private $postDataModel;
    private $voteModel;

    public function __execute() {
        $this->type = 'interface';

        if( is_null( $this->account ) ) {
            $this->error( 'NOTLOGIN_ERR' );
        }

        $this->paramsProcessing();

        $this->postDataModel = new PostDataModel();

        $this->checkPost();

        $this->voteModel = new VoteModel();

        $this->addVote();

        return $this->data;
    }

    private function addVote() {
        $params = $this->params;

        $vote = $this->voteModel->getVoteByPostAndAccount( 
            $params[ 'post_id' ], 
            $this->account[ 'id' ],
            $params[ 'opinion' ],
            $params[ 'type' ]
        );

        if( $vote ) {
            $this->error( 'REACHED_MAX' );
        } else {

            $transactionModel = new TransactionModel();

            $transactionModel->vote( array(
                'post_id' => $params[ 'post_id' ],
                'account_id' => $this->account[ 'id' ],
                'opinion' => $params[ 'opinion' ],
                'type' => $params[ 'type' ],
                'value' => $params[ 'value' ]
            ),  $this->targetPostData );
        }
    }

    private function paramsProcessing() {
        $request = $this->request;

        $post_id = $request->getPost( 'post_id' );

        if( is_null( $post_id ) ) {
            $this->error( 'PARAMS_ERR' );
        }

        $opinion = $request->getPost( 'opinion' );

        if( is_null( $opinion ) ) {
            $this->error( 'PARAMS_ERR' );
        }

        $type = $request->getPost( 'type' );

        if( is_null( $type ) || !in_array( $type, [ 0, 1, 2 ] ) ) {
            $this->error( 'PARAMS_ERR' );
        }

        $value = intval( $request->getPost( 'value' ) );

        if( $value <= 0 ) {
            $this->error( 'PARAMS_ERR' );
        }

        if( $type == 0 && $value != 1 ) {
            $this->error( 'PARAMS_ERR' );
        }

        if( ( $type == 1 || $type == 2 ) && $type > 20 ) {
            $this->error( 'PARAMS_ERR' );
        }

        $value = 1;

        $this->params = [
            'post_id' => $post_id,
            'opinion' => $opinion,
            'value' => $value,
            'type' => $type
        ];

        return $this;
    }

    private function checkPost() {
        $post = $this->postDataModel->get( $this->params[ 'post_id' ] );
        if( !$post ) {
            $this->error( 'POST_NOTEXISTS' );
        }

        $this->targetPostData = $post;

        return $this;
    }

}