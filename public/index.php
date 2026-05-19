<?php
require_once '../vendor/autoload.php';

require_once "../controllers/MainController.php";
require_once "../controllers/CarTypeController.php";
require_once "../controllers/DeleteCarController.php";
require_once "../controllers/EditCarController.php";
require_once "../controllers/Router.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/CreateController.php";
require_once "../controllers/CreateAgeController.php";
require_once "../controllers/CarController.php";
require_once "../controllers/Controller404.php";
require_once "../controllers/RegisterController.php";
require_once "../controllers/LoginController.php";

require_once "../middlewares/LoginRequiredMiddleware.php";
require_once "../middlewares/HistoryMiddleware.php";

$loader = new \Twig\Loader\FilesystemLoader(['../views', './macros']);

$twig = new \Twig\Environment($loader, [
    "debug" => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addGlobal('current_uri', $_SERVER['REQUEST_URI']);

$pdo = new PDO("mysql:host=localhost;dbname=cars;charset=utf8", "root", "root");

$url = $_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$queryString = parse_url($url, PHP_URL_QUERY) ?? '';
$params = [];

$page_path = trim($path, '/');

$router = new Router($twig, $pdo);

$router->add("#^/$#", MainController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#^/main$#", MainController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#^/ages/(?P<age>[a-zA-Z0-9\-_]+)#", CarTypeController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#^/cars/(?P<short_name>[a-zA-Z0-9\-_]+)/edit$#", EditCarController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#^/cars/(?P<short_name>[a-zA-Z0-9\-_]+)/delete$#", DeleteCarController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#/cars/#", CarController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#/add_age#", CreateAgeController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#/add#", CreateController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#/search#", SearchController::class)->middleware(new LoginRequiredMiddleware())
    ->middleware(new HistoryMiddleware());
$router->add("#/register#", RegisterController::class);
$router->add("#/login#", LoginController::class);

$router->get_or_default(Controller404::class);
