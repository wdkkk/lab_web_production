<?php
require_once "BaseMiddleware.php";

class LoginRequiredMiddleware extends BaseMiddleware
{
    public function apply(BaseController $controller, array $context)
    {
        if (isset($_GET['logout'])) {
            setcookie("user_id", "", time() - 3600, "/");
            header('Location: /login');
            exit();
        }

        $is_authenticated = isset($_COOKIE['user_id']);

        if (!$is_authenticated) {
            header('Location: /login');
            exit();
        }
    }
}
