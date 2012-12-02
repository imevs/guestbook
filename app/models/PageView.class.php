<?php
require_once 'configs.php';

/**
 */
class PageView
{
    public $configs = array();

    public function setConfigs($configs)
    {
        $this->configs = $configs;
    }

    public function getConfig($name)
    {
        return $this->configs[$name];
    }

    public function outputCSS()
    {
        $css = $this->getConfig('css');
        $result = '';
        foreach($css as $file ) {
            $result .= <<<LINK
<link href="$file" rel="stylesheet">
LINK;
        }
        return $result;
    }

    public function outputJS()
    {
        $js_scripts = $this->getConfig('js');
        $result = '';
        foreach($js_scripts as $file ) {
            if ($file['condition']) {
                $result .= "<!--[{$file['condition']}]>";
            }
            $result .= <<<JS
            <script src="{$file['src']}">
JS;
            if ($file['condition']) {
                $result .= '<![endif]-->' ;
            }
        }
        return $result;
    }

    public function render($template, $data = array())
    {
        $file = 'app/views/' . $template . '.php';
        return $this->renderFile($file, $data);
    }

    function renderFile($file, $data)
    {
        $data['gb_user'] = User::getCurrentUser();
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
        $file = 'app/actions/' . $this->getCurrentAction() . '.php';
        return $this->renderFile($file, $params);
    }

    public function renderPage()
    {
        $data = array('title' => $this->getConfig('title'));
        return $this->render('layout', $data);
    }

    public static function getInstance()
    {
        static $instance;
        return $instance ? $instance : $instance = new PageView();
    }

    private function __construct(){

    }
}

PageView::getInstance()->setConfigs(array(
    'css' => $css,
    'js' => $js_scripts,
    'title' => $title
));

function render($template, $data = array()) {
    return PageView::getInstance()->render($template, $data);
}

function output_css() {
    return PageView::getInstance()->outputCSS();
}

function output_js() {
    return PageView::getInstance()->outputJS();
}

function render_body($params) {
    return PageView::getInstance()->renderBody($params);
}