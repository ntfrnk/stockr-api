<?php

namespace App\Classes;

class MakeResponse 
{
	protected $response;

    public static function success()
    {
    	$instance = new self();
        $instance->response = [
	        'url' => request()->url(),
	        'code' => 200,
	        'status' => 'success',
	        'message' => 'Its Okay!'
	    ];
        return $instance;
    }

    public static function error()
    {
    	$instance = new self();
        $instance->response = [
	        'url' => request()->url(),
	        'code' => 400,
	        'status' => 'error',
	        'message' => 'Something is wrong...'
	    ];
	    return $instance;
    }

    public static function body($body)
    {
        $instance = new self();
        $instance->response = $body;
        return $instance;
    }

    public function code($code = 200)
    {
        $this->response['code'] = $code;
        return $this;
    }

    public function message($message = '')
    {
        $this->response['message'] = $message;
        return $this;
    }

    public function data($data = [])
    {
        $this->response['data'] = $data;
        return $this;
    }

    public function errors($errors = [])
    {
        $this->response['errors'] = $errors;
        return $this;
    }

    public function withURL()
    {
        $this->response['url'] = request()->url();
        return $this;
    }

    public function withoutURL()
    {
        unset($this->response['url']);
        return $this;
    }

    public function withoutStatus()
    {
        unset($this->response['status']);
        return $this;
    }

    public function withoutMessage()
    {
        unset($this->response['message']);
        return $this;
    }

    public function addKey($key, $value = '')
    {
        $this->response[$key] = $value;
        return $this;
    }

    public function get($file = '', $line = '')
    {
        return response()->json($this->response, $this->response['code']);
    }
}