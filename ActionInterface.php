<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

interface ActionInterface
{

    function run(array $params = []);

    function render(string $view, array $params = []) : string;

}