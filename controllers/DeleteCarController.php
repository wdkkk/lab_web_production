<?php

class DeleteCarController extends BaseController
{
    public function post(array $context)
    {
        $parts = explode('/', $_SERVER['REQUEST_URI']);
        $short_name = $parts[2];

        $sql = <<<EOL
            DELETE FROM cars_table WHERE short_name = :short_name
        EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue(":short_name", $short_name);
        $query->execute();

        header("Location: /");
        exit;
    }
}
