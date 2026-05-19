    <?php

    class Route
    {
        public string $route_regexp; // тут получается шаблона url
        public $controller; // а это класс контроллера
        public array $middlewareList = [];

        public function middleware(BaseMiddleware $m): Route
        {
            array_push($this->middlewareList, $m);
            return $this;
        }

        // ну и просто конструктор
        public function __construct($route_regexp, $controller)
        {
            $this->route_regexp = $route_regexp;
            $this->controller = $controller;
        }
    }

    class Router
    {
        /**
         * @var Route[]
         */
        protected $routes = []; // создаем поле -- список под маршруты и привязанные к ним контроллеры

        protected $twig;
        protected $pdo;

        // конструктор
        public function __construct($twig, $pdo)
        {
            $this->twig = $twig;
            $this->pdo = $pdo;
        }

        // функция с помощью которой добавляем маршрут
        public function add($route_regexp, $controller)
        {
            $route = new Route($route_regexp, $controller);
            array_push($this->routes, $route);

            // возвращаем как результат функции
            return $route;
        }

        public function get_or_default($default_controller)
        {
            $url = $_SERVER["REQUEST_URI"];

            $path = parse_url($url, PHP_URL_PATH);

            $controller = $default_controller;
            $newRoute = null; // добавили переменную под маршрут

            $matches = [];
            foreach ($this->routes as $route) {
                if (preg_match($route->route_regexp, $path, $matches)) {
                    $controller = $route->controller;
                    $newRoute = $route; // загоняем соответствующий url маршрут в переменную
                    break;
                }
            }


            $controllerInstance = new $controller();
            $controllerInstance->setPDO($this->pdo);

            if ($controllerInstance instanceof TwigBaseController) {
                $controllerInstance->setTwig($this->twig);
            }

            if ($newRoute) {
                foreach ($newRoute->middlewareList as $m) {
                    $m->apply($controllerInstance, []);
                }
            }
            return $controllerInstance->process_response();
        }
    }
