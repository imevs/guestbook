<?php
$errors = MessageCollection::process_comments();

$data = array(
    'comments' => MessageCollection::get_comments(),
    'errors' => $errors,
);

echo render ('content', $data);