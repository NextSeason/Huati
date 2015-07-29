<?php

Class ArticleModel extends BaseModel {
    protected $table = 'articles';

    /**
     * get articles data by a topic_id
     */
    public function getArticlesByTopic( $params ) {
        $topic_id = $params['topic_id'];
        $cursor = intval( $params['cursor'] );
        $rn = $params['rn'];

        if( $cursor == 0 ) {
            $query = 'SELECT `id`, `img`, `title`, `summary`, `time`, `origin`, `origin_url`, `author` FROM `articles` WHERE `topic_id`=:topic_id ORDER BY `id` DESC LIMIT :rn';
        }  else {
            $query = 'SELECT `id`, `img`, `title`, `summary`, `time`, `origin`, `origin_url`, `author` FROM `articles` WHERE `topic_id`=:topic_id AND `id`<:cursor ORDER BY `id` DESC LIMIT :rn';
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare( $query );
            $stmt->bindValue( ':topic_id', $topic_id );
            $stmt->bindValue( ':rn', (int)$rn, PDO::PARAM_INT );
            if( $cursor != 0 ) {
                $stmt->bindValue( ':cursor', $cursor );
            }

            if( !$stmt->execute() ) {
                throw new PDOException( 'failed to get data from articles' );
            }

            $articles = $stmt->fetchAll( PDO::FETCH_ASSOC );

            $this->db->commit();
            return $articles;

        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * get all articles without any conditions
     * always use for the article list page in mis
     */
    public function getArticles( $params ) {
        $start = $params['start'];
        $rn = $params['rn'];

        $query = 'SELECT * FROM `articles` ORDER BY `id` DESC LIMIT :start, :rn';
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare( $query );

            $stmt->bindValue( ':start', (int)$start, PDO::PARAM_INT );
            $stmt->bindValue( ':rn', (int)$rn, PDO::PARAM_INT );

            if( !$stmt->execute() ) {
                throw new PDOException( 'failed to get data from articles' );
            }

            $articles = $stmt->fetchAll( PDO::FETCH_ASSOC );

            $this->db->commit();
            return $articles;
        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }

    public function getPrevious( $id, $topic_id ) {
        $query = 'SELECT `id`, `title` FROM `articles` WHERE `topic_id`=:topic_id AND `id`>:id LIMIT 1';

        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare( $query );
            $stmt->bindValue( ':topic_id', $topic_id );
            $stmt->bindValue( ':id', $id );

            if( !$stmt->execute() ) {
                throw new PDOException( 'failed to get data from articles' );
            }

            $article = $stmt->fetch( PDO::FETCH_ASSOC );

            $this->db->commit();

            return $article;
        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }

    public function getNext( $id, $topic_id ) {
        $query = 'SELECT `id`, `title` FROM `articles` WHERE `topic_id`=:topic_id AND `id`<:id ORDER BY `id` DESC LIMIT 1';

        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare( $query );
            $stmt->bindValue( ':topic_id', $topic_id );
            $stmt->bindValue( ':id', $id );

            if( !$stmt->execute() ) {
                throw new PDOException( 'failed to get data from articles' );
            }

            $article = $stmt->fetch( PDO::FETCH_ASSOC );

            $this->db->commit();

            return $article;
        } catch( PDOException $e ) {
            $this->db->rollback();
            return false;
        }
    }


}