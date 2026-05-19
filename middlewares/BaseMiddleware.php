<?php

abstract class BaseMiddleware
{
    public function apply(BaseController $controller, array $context) {}
}
