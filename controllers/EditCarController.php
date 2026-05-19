<?php
require_once "TwigBaseController.php";

class EditCarController extends TwigBaseController
{
    public $template = "./pages/edit.twig";

    public function get(array $context)
    {
        $parts = explode('/', $_SERVER['REQUEST_URI']);
        $short_name = $parts[2];

        $sql = <<<EOL
            SELECT * FROM cars_table WHERE short_name = :short_name
        EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue(":short_name", $short_name);
        $query->execute();

        $car = $query->fetchAll()[0];

        $context['car'] = $car;

        echo $this->twig->render($this->template, $context);
    }

    public function post(array $context)
    {
        $title = $_POST['name'];
        $description = $_POST['description'];
        $age = $_POST['age'];
        $short_name = $_POST["short_name"];

        $tmp_name = $_FILES['formFile']['tmp_name'];
        $name =  $_FILES['formFile']['name'];

        move_uploaded_file($tmp_name, "../public/assets/$name");

        $sql = <<<EOL
UPDATE cars_table 
SET 
    title = :title, 
    description = :description, 
    age = :age, 
    image = "../assets/$name" 
WHERE short_name = :short_name;
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("age", $age);
        $query->bindValue("short_name", $short_name);

        $query->execute();

        $context['id'] = $this->pdo->lastInsertId();
        $context["message"] = "Автомобиль успешно изменен!";

        $this->get($context);
    }
}
