<?php


class View {
    protected $layout = 'layout';
    protected $view;

    public function __construct(){

    }

    public function setView($view) {
        $this->view = $view;
    }

    public function render($view = '',$vars = []){
        if (empty($view)) {
            $view = $this->view;
        }
        if (is_array($vars)) {
            extract($vars);
        }
        $fileView =  ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR .$view . '.php';
        ob_start();
        if (is_file($fileView)){
            require $fileView;
        } else {
            echo "<p>Не найден вид <b>$fileView</b></p>";
        }
        $content = ob_get_clean();

        $fileLayout = ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $this->layout . '.php';
        if (is_file($fileLayout)){
            require $fileLayout;
        } else {
            echo "<p>Не найден шаблон <b>$fileLayout</b></p>";
        }

    }
}