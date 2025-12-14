<?php
// app/core/App.php
class App {
    protected $controller = 'HomeController'; // Controller mặc định
    protected $action = 'index';              // Hàm mặc định
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Kiểm tra xem file Controller có tồn tại không
        if (isset($url[0])) {
            if (file_exists(APP_ROOT . '/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
                $this->controller = ucfirst($url[0]) . 'Controller';
                unset($url[0]);
            }
        }

        // Import Controller đó vào
        require_once APP_ROOT . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. Kiểm tra Action (Hàm)
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->action = $url[1];
                unset($url[1]);
            }
        }

        // 3. Lấy tham số (Params)
        $this->params = $url ? array_values($url) : [];

        // 4. Gọi hàm chạy: Controller->Action(Params)
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    // Hàm tách URL
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}
?>