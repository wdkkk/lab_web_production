<?php
abstract class BaseController
{
    public PDO $pdo;

    public function setPDO(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getContext(): array
    {
        $array = [];

        if (isset($_SESSION) && isset($_SESSION["history"]) && is_array($_SESSION["history"]))
            $array = [
                "pages_history" => $_SESSION["history"]
            ];


        return $array;
    }

    public function process_response()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $context = $this->getContext();

        if ($method == 'GET') {
            $this->get($context); // а тут просто его пробрасываю внутрь
        } else if ($method == 'POST') {
            $this->post($context); // и здесь
        }
    }

    public function get(array $context) {} // ну и сюда добавил в качестве параметра 
    public function post(array $context) {} // и сюда
}
