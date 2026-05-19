<?php
require_once "BaseController.php";

class TwigBaseController extends BaseController
{
    public $title = "";
    public $template = "";
    protected \Twig\Environment $twig;

    public function getContext(): array
    {
        $context = parent::getContext();
        $query = $this->pdo->query("SELECT * FROM cars_table");
        $context['cars'] = $query->fetchAll();

        $query = $this->pdo->query("SELECT * FROM ages");
        $context['ages'] = $query->fetchAll();

        return $context;
    }

    public function setTwig($twig)
    {
        $this->twig = $twig;
    }


    public function get(array $context)
    {
        echo $this->twig->render($this->template, $context);
    }
}
