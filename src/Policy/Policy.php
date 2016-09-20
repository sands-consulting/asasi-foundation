<?php

namespace Sands\Asasi\Foundation\Policy;

use Illuminate\Contracts\Auth\Guard;
use Sands\Asasi\Foundation\Policy\Exceptions\PolicyDoesNotExists;
use Sands\Asasi\Foundation\Policy\Exceptions\PolicyMethodDoesNotExists;

class Policy
{
    protected $auth;
    protected $policies = [];
    private $cached     = [];

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function check($controller, $action, $parameters = [])
    {
        $user       = $this->auth->user;
        $parameters = $parameters ?: [];

        if (!isset($this->policies[$controller])) {
            throw new PolicyDoesNotExists($controller);
        }

        $handler = $this->getPolicy($controller);
        if (!method_exists($handler, $action)) {
            throw new PolicyMethodDoesNotExists($controller, $action);
        }

        return call_user_func_array([$handler, $action], array_merge([$user], $parameters));
    }

    public function checkCurrentRoute()
    {
        $route                     = app()->make('router')->current();
        list($controller, $action) = explode('@', $route->getAction()['uses']);
        return $this->check($controller, $action, $route->parameters());
    }

    public function register($controller, $handler)
    {
        $this->policies[$controller] = $handler;
    }

    protected function getPolicy($controller)
    {
        if (!isset($this->cached[$controller])) {
            $this->cached[$controller] = new $this->policies[$controller]($this->auth);
        }
        return $this->cached[$controller];
    }
}
