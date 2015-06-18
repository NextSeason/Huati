<?php

Class NewTopicAction extends \Local\MisAction {

    private $data = array();
    private $topicModel;

    public function __execute() {

        $this->type = 'interface';

        $this->paramsProcessing();

        $this->topicModel = new TopicModel();
        
        $topic = $this->addTopic();

        if( !$topic ) {
            $this->error( 'SYSTEM_ERR' );
        }

        $this->data[ 'id' ] = $topic;

        return $this->data;
    }
    
    private function addTopic() {
        $params = $this->params;

        $data = array(
             'cid' => $params[ 'cid' ],
            'type' => $params[ 'type' ],
            'status' => $params[ 'isPublic' ],
            'title' => $params[ 'title' ],
            'desc' => $params[ 'desc' ]
        );

        if( isset( $params[ 'start' ]  ) )  {
            $data[ 'start' ] = $params[ 'start' ];
        }

        return $this->topicModel->insert( $data );
    }

    private function paramsProcessing() {
        $request = $this->request;

        $title = $request->getPost( 'title' );

        if( is_null( $title ) ) {
            $this->error( 'PARAMS_ERR', 'Topic title is null' );
        }

        $len = strlen( $title );

        if( !$len ) {
            $this->error( 'PARAMS_ERR', 'Topic title is null' );
        }

        if( $len > 120 ) {
            $this->error( 'PARAMS_ERR', 'Topic title is too long' );
        }

        $desc = $request->getPost( 'desc' );

        if( is_null( $desc ) || !strlen( $desc) ) {
            $this->error( 'PARAMS_ERR', 'Topic description is empty' );
        }

        $type = $request->getPost( 'type' );

        if( !isset( $type ) || !in_array( $type, array( 0, 1 ) ) ) {
            $this->error( 'PARAMS_ERR', 'You need to select a type for this topic' );
        }

        $cid = $request->getPost( 'cid' );

        if( empty( $cid ) ) {
            $this->error( 'PARAMS_ERR', 'You must to select a category for this topic' ); 
        }

        $isPublic = $request->getPost( 'isPublic' );

        $isPublic = !isset( $isPublic ) || $isPublic == 0 ? 0 : 1;

        $this->params = array(
            'title' => $title,
            'desc' => $desc,
            'type' => $type,
            'cid' => $cid,
            'isPublic' => $isPublic
        );

        $start = $request->getPost( 'start' );

        if( !empty( $start ) ) {
            $this->params[ 'start' ] = $start;
        }

        return $this;
    }
}
