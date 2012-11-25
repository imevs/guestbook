<?php
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

function get_comments()
{
    global $comments;

    $filename = $_SERVER['DOCUMENT_ROOT'] . '/guestbook/data/data.txt';
    $ar_comments = get_comments_from_file($filename);
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
    $filename = $_SERVER['DOCUMENT_ROOT'] . '/guestbook/data/data.txt';
    $comments = get_comments_from_file($filename);
    $comments[] = array(
        'author' => htmlspecialchars($comment['author']),
        'text' => htmlspecialchars($comment['text'])
    );
    $result = json_encode($comments);
    file_put_contents($filename, $result);
}

function process_comments()
{
    if (isCommentSended()) {

        $comment = array(
            'author' => $_REQUEST['comment_author'],
            'text' => $_REQUEST['comment_text']
        );
        $result = checkComment($comment);

        if ($result === true)   {
            save_comment($comment);
            header('Location: index.php');
        } else {
            return $result;
        }
    }
}