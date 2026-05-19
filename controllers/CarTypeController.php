<?php
require_once "TwigBaseController.php";

class CarTypeController extends TwigBaseController
{
    public $template = "pages/main.twig";
    private $age = "";

    public function __construct()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $parts = explode('/', $uri);

        $this->age = $parts[1];
    }

    public function getContext(): array
    {
        $context = parent::getContext();

        $query = $this->pdo->query("SELECT * FROM ages");
        $context['ages'] = $query->fetchAll();

        if (!in_array($this->age, array_column($context['ages'], 'age')))
            header("Location: /404");

        $query = $this->pdo->query("SELECT * FROM cars_table WHERE age = '" . $this->age . "';");
        $context['cars'] = $query->fetchAll();

        return $context;
    }
}
