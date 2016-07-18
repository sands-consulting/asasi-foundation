<?php

namespace Sands\Asasi\Foundation\Http;

use Sands\Asasi\Foundation\Http\Exceptions\RuleNotExists;
use Illuminate\Foundation\Http\FormRequest;

abstract class FormRequest extends FormRequest
{
    private function getCurrentRoute()
    {
    	$route = $this->app->router->current();
    	return list($controller, $action) = explode('@', $route->getAction()['uses']);
    }

    public function rules()
    {
    	switch (true) {
    		case method_exists($this, $this->getCurrentRoute()[1] . 'Rules'):
    			return call_user_func($this, $this->getCurrentRoute()[1] . 'Rules');
    			break;
    		
    		case method_exists($this, $this->method() . 'Rules'):
    			return class_user_func($this, $this->method() . 'Rules');
    			break;

    		default:
    			throw new RuleNotExists($this->getCurrentRoute()[0])
    			break;
    	}
    }
}
