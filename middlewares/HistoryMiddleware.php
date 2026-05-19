<?php
require_once "BaseMiddleware.php";

class HistoryMiddleware extends BaseMiddleware
{
    public function apply(BaseController $controller, array $context)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["history"]) || !is_array($_SESSION["history"])) {
            $_SESSION["history"] = [];
        }

        array_unshift(
            $_SESSION["history"],
            $_SERVER['REQUEST_URI']
        );


        return $_SESSION["history"];
    }
}
