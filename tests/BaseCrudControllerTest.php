<?php

use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use Psr\Log\NullLogger;

class BaseCrudControllerTest extends \CodeIgniter\Test\CIUnitTestCase
{

    protected function createController()
    {
        $request = new Request(config('app'));

        $response = new Response(config('app'));

        $logger = new NullLogger;

        $controller = new class extends \BasicApp\Crud\BaseCrudController {};

        $controller->initController($request, $response, $logger);
    
        return $controller;
    }

    public function testIndex()
    {
        $controller = $this->createController();

        $result = $controller->_remap('index');
    }

    public function testCreate()
    {
        $controller = $this->createController();

        $result = $controller->_remap('create');
    }

    public function testUpdate()
    {
        $controller = $this->createController();

        $result = $controller->_remap('update');
    }

    public function testView()
    {
        $controller = $this->createController();

        $result = $controller->_remap('view');
    }

    public function testDelete()
    {
        $controller = $this->createController();

        $result = $controller->_remap('delete');
    }

}