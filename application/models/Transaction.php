<?php

Class TransactionModel extends BaseModel {
    public function addPost( $data ) {
        /**
         * insert new post into table posts
         */
        try {
            $this->db->beginTransaction();

            /**
             * insert new post into database
             */
            $post_id = $this->insert( $data, 'posts' );

            $this->insert( [ 'id' => $post_id ], 'posts_data' );

            $topic_id = $data[ 'topic_id' ];

            /**
             * update post_cnt in table topics_data
             */
            $this->increment( $topic_id, [ 'post_cnt' => 1 ], 'topics_data' );

            if( isset( $data[ 'point_id' ] ) ) {
                $this->increment( $data[ 'point_id' ], [ 'post_cnt' => 1 ], 'points_data'  );
            }

            $this->db->commit();

            return $post_id;

        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }

    public function addTopic( $data ) {

        try {
            $this->db->beginTransaction();

            $topic_id = $this->insert( $data, 'topics' );

            /**
             * insert new data into table topics_data
             */
            $this->insert( array( 'id' => $topic_id ), 'topics_data' );

            $this->db->commit();

            return $topic_id;
        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }

    public function addAccount( $data ) {

        try {
            $this->db->beginTransaction();

            $account_id = $this->insert( $data, 'accounts' );

            $this->db->commit();

            return $account_id;
        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }
}
