<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud;

abstract class BaseCrudController extends \BasicApp\Controller\BaseController
{

    protected $actions = [
        'index' => [\BasicApp\Crud\Actions\ListAction::class, 25],
        'create' => \BasicApp\Crud\Actions\CreateAction::class,
        'update' => \BasicApp\Crud\Actions\UpdateAction::class,
        'view' => \BasicApp\Crud\Actions\ViewAction::class,
        'delete' => \BasicApp\Crud\Actions\DeleteAction::class
    ];

}