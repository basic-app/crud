<?php

namespace BasicApp\Crud;

use BasicApp\Crud\Actions\ListAction;
use BasicApp\Crud\Actions\CreateAction;
use BasicApp\Crud\Actions\UpdateAction;
use BasicApp\Crud\Actions\ViewAction;
use BasicApp\Crud\Actions\DeleteAction;

abstract class BaseCrudController extends \BasicApp\Controller\BaseController
{

    protected $actions = [
        'index' => [ListAction::class, 25],
        'create' => [CreateAction::class],
        'update' => [UpdateAction::class],
        'view' => [ViewAction::class],
        'delete' => [DeleteAction::class]
    ];

}