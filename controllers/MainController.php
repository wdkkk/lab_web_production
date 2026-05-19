<?php
require_once "TwigBaseController.php";

class MainController extends TwigBaseController
{
    public $template = "pages/main.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        $query = $this->pdo->query("SELECT * FROM cars_table");
        $context['cars'] = $query->fetchAll();

        return $context;
    }
}
