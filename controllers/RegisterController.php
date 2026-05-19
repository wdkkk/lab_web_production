<?php
require_once "TwigBaseController.php";

class RegisterController extends TwigBaseController
{
    public $template = "pages/auth.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $context["title"] = "Регистрация";
        $context["button_content"] = "Зарегистрироваться";

        return $context;
    }

    public function post(array $context)
    {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = <<<EOL
INSERT INTO users (username, hash)
VALUES(:username, :hash)
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("username", $login);
        $query->bindValue("hash", $hash);

        $query->execute();

        $context['id'] = $this->pdo->lastInsertId();
        $context["message"] = "Вы успешно зарегистрированы!";

        $this->get($context);
    }
}
