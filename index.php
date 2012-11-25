<?php
include 'data/data.php';
include 'code/user.php';
include 'code/functions.php';
include 'code/comments_functions.php';

$data = array('title' => $title);

echo render('layout', $data);