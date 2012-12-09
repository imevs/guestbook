<?php

class MessageCollection
{

    /**
     * @param $filename
     * @return array
     */
    function get_comments_from_file($filename)
    {
        $comments = array();
        if (file_exists($filename)) {
            $content = file_get_contents($filename);
            $comments = json_decode($content, true);
            if (!is_array($comments)) {
                $comments = array();
                return $comments;
            }
            return $comments;
        }
        return $comments;
    }

    public function getCommentsFromSqlite()
    {
        $db_filename = 'data.sqlite';
        $db = new PDO('sqlite:'. $db_filename);

        $stmt = $db->prepare('select * from messages');
        $stmt->execute();
        $stmt->bindColumn(1, $type, PDO::PARAM_STR, 256);
        $stmt->bindColumn(2, $lob, PDO::PARAM_LOB);
        $stmt->fetch(PDO::FETCH_BOUND);
    }

    public static function get_comments()
    {
        global $comments;

        $filename = APP_PATH . '/data/data.txt';
        $ar_comments = self::get_comments_from_file($filename);
        return array_merge($comments, $ar_comments);
    }

    function isCommentSended()
    {
        return isset($_REQUEST['comment_author']);
    }

    function checkComment($comment)
    {
        $errors = array();
        if (!$comment['text']) {
            $errors[] = 'empty message';
        }
        return count($errors) ? $errors : true;
    }

    function save_comment($comment)
    {
        $filename = APP_PATH . '/data/data.txt';
        $comments = self::get_comments_from_file($filename);
        $comments[] = array(
            'author' => htmlspecialchars($comment['author']),
            'text' => htmlspecialchars($comment['text'])
        );
        $result = json_encode($comments);
        file_put_contents($filename, $result);
    }

    public static function process_comments()
    {
        if (self::isCommentSended()) {

            $comment = array(
                'author' => $_REQUEST['comment_author'],
                'text' => $_REQUEST['comment_text']
            );
            $result = self::checkComment($comment);

            if ($result === true)   {
                self::save_comment($comment);
                header('Location: index.php');
            } else {
                return $result;
            }
        }
        return '';
    }

    private $messages = array();

    public function getMessages()
    {
        return $this->messages;
    }

    public function add(Message $item)
    {
        $this->messages[] = $item;
    }
}
