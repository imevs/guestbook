<?php
function outputCSS()
{
    global $css;
    foreach($css as $file ) {
        echo <<<LINK
<link href="$file" rel="stylesheet">
LINK;
    }
}

function outputJS()
{
    global $js_scripts;
    foreach($js_scripts as $file ) {
        if ($file['condition']) {
            echo "<!--[{$file['condition']}]>";
        }
        echo <<<JS
            <script src="{$file['src']}">
JS;
        if ($file['condition']) {
            echo '<![endif]-->' ;
        }
    }
}

function render($template, $data = array())
{
    $file = 'blocks/' . $template . '.php';
    return renderFile($file, $data);
}

function renderFile($file, $data)
{
    ob_start();
    if (!file_exists($file)) {
        echo <<<ERROR
<div class="alert">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>Warning!</strong> File $file doesn't exists
</div>
ERROR;
    } else {
        extract($data);
        include $file;
    }
    return ob_get_clean();
}

function getCurrentAction()
{
    $action = $_GET['action'];
    $actions = array('main', 'about', 'edit', 'logout', 'login');

    return isset($action)
        ? (in_array($action, $actions) ? $action : 'error')
        : 'main';
}

function renderBody($params)
{
    $file = 'pages/' . getCurrentAction() . '.php';
    return renderFile($file, $params);
}