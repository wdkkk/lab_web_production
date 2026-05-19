<?php
require_once "TwigBaseController.php";

class LoginController extends TwigBaseController
{
    public $template = "pages/auth.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $context["title"] = "Вход";
        $context["button_content"] = "Войти";

        return $context;
    }

    public function post(array $context)
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $sql = "SELECT id, hash FROM users WHERE username = :username";
        $query = $this->pdo->prepare($sql);
        $query->bindValue("username", $login);
        $query->execute();

        $user = $query->fetch();


        if ($user && password_verify($password, $user['hash'])) {
            setcookie("user_id", $user['id'], time() + (86400 * 30), "/");

            header("Location: /main");
            return;
        }

        $this->get($context);
    }
}
