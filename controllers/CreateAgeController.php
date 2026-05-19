<?php
require_once "TwigBaseController.php";

class CreateAgeController extends TwigBaseController
{
    public $template = "./pages/create_type.twig";

    public function post(array $context)
    {
        $age = $_POST['age'];
        $translation = $_POST['translation'];

        $sql = <<<EOL
INSERT INTO ages (age, translation)
VALUES(:age, :translation)
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("age", $age);
        $query->bindValue("translation", $translation);

        $query->execute();

        $context['id'] = $this->pdo->lastInsertId();
        $context["message"] = "Новый тип успешно добавлен!";

        $this->get($context);
    }
}
