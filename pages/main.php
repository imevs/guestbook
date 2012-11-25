<?php

$errors = process_comments();

$data = array(
    'comments' => get_comments(),
    'errors' => $errors,
);

echo render ('content', $data);