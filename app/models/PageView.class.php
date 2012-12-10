<?php
require_once dirname(__FILE__) . '/../config/configs.php';

/**
 */
class PageView
{
    public static $disabledViews = array();
    public static $currentView = '';

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
        if (isset(self::$disabledViews[$template])) {
            return self::$disabledViews[$template];
        }
        self::$currentView = $template;
        $file = 'app/views/' . $template . '.php';
        return $this->renderFile($file, $data);
    }

    function renderFile($file, $data)
    {
        $data['gb_user'] = User::getCurrentUser();
        ob_start();
        $file = APP_DIR . $file;
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
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
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

    private function __construct()
    {
        // определеяем уровень протоколирования ошибок
        error_reporting(E_ALL | E_STRICT);
        // определяем режим вывода ошибок
        ini_set('display_errors', 'On');
        // включаем буфферизацию вывода (вывод скрипта сохраняется во внутреннем буфере)
        ob_start();
        // устанавливаем пользовательский обработчик ошибок
        set_error_handler(array("PageView", "error_handler"));
        // регистрируем функцию, которая выполняется после завершения работы скрипта (например, после фатальной ошибки)
        register_shutdown_function(array('PageView', 'fatal_error_handler'));
    }


    /**
     * Обработчик ошибок
     * @param int $errno уровень ошибки
     * @param string $errstr сообщение об ошибке
     * @param string $errfile имя файла, в котором произошла ошибка
     * @param int $errline номер строки, в которой произошла ошибка
     * @return boolean
     */
    public static function error_handler($errno, $errstr, $errfile, $errline)
    {
        // если ошибка попадает в отчет (при использовании оператора "@" error_reporting() вернет 0)
        if (error_reporting() & $errno) {
            $errors = array(
                E_ERROR => 'E_ERROR',
                E_WARNING => 'E_WARNING',
                E_PARSE => 'E_PARSE',
                E_NOTICE => 'E_NOTICE',
                E_CORE_ERROR => 'E_CORE_ERROR',
                E_CORE_WARNING => 'E_CORE_WARNING',
                E_COMPILE_ERROR => 'E_COMPILE_ERROR',
                E_COMPILE_WARNING => 'E_COMPILE_WARNING',
                E_USER_ERROR => 'E_USER_ERROR',
                E_USER_WARNING => 'E_USER_WARNING',
                E_USER_NOTICE => 'E_USER_NOTICE',
                E_STRICT => 'E_STRICT',
                E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
                //E_DEPRECATED => 'E_DEPRECATED',
                //E_USER_DEPRECATED => 'E_USER_DEPRECATED',
            );

            $currentView = self::$currentView;
            self::$disabledViews[self::$currentView] = <<<ERROR
<div class="alert">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>Error!</strong> Fatal error in file {$currentView} [$errno] $errstr (на $errline строке)
</div>
ERROR;

            // выводим свое сообщение об ошибке
            $pageView = PageView::getInstance();
            echo $pageView->renderPage();
            //echo "<b>{$errors[$errno]}</b>[$errno] $errstr ($errfile на $errline строке)<br />\n";
        }

        // не запускаем внутренний обработчик ошибок PHP
        return TRUE;
    }

    /**
     * Функция перехвата фатальных ошибок
     */
    public static function fatal_error_handler()
    {
        // если была ошибка и она фатальна
        if ($error = error_get_last() AND $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            // очищаем буффер (не выводим стандартное сообщение об ошибке)
            while (ob_get_level()) {
                ob_end_clean();
            }
            // запускаем обработчик ошибок
            self::error_handler($error['type'], $error['message'], $error['file'], $error['line']);
        } else {
            // отправка (вывод) буфера и его отключение
            ob_end_flush();
        }
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