<?php
require_once "TwigBaseController.php";

class CarController extends TwigBaseController
{
    public $car_name = "";
    public $template = "pages/car_image.twig";

    public function __construct()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $parts = explode('/', $uri);

        $this->car_name = end($parts);

        if (isset($_GET['desc'])) {
            $this->template = "pages/car_desc.twig";
        } else {
            $this->template = "pages/car_image.twig";
        }
    }


    public function getContext(): array
    {
        $context = parent::getContext();
        $query = $this->pdo->query("SELECT * FROM cars_table WHERE short_name='" . $this->car_name . "'");

        $context["car"] = $query->fetchAll()[0];

        $query = $this->pdo->query("SELECT * FROM cars_table");
        $context['cars'] = $query->fetchAll();

        return $context;
    }
}
