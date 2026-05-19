<?php
require_once "TwigBaseController.php";

class CreateController extends TwigBaseController
{
    public $template = "./pages/create.twig";


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
INSERT INTO cars_table(title, description, age, short_name, image)
VALUES(:title, :description, :age, :short_name, "../assets/$name")
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("age", $age);
        $query->bindValue("short_name", $short_name);

        $query->execute();

        $context['id'] = $this->pdo->lastInsertId();
        $context["message"] = "Автомобиль успешно добавлен!";

        $this->get($context);
    }
}
