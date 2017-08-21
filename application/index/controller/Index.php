<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return view('index', ['name' => 'hello world']);
    }

    public function login($name)
    {
    	return $name;
    }
}
