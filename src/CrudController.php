<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud;

use BasicApp\Crud\Actions\IndexAction;
use BasicApp\Crud\Actions\CreateAction;
use BasicApp\Crud\Actions\UpdateAction;
use BasicApp\Crud\Actions\ViewAction;
use BasicApp\Crud\Actions\DeleteAction;

abstract class CrudController extends \BasicApp\Controller\BaseController
{

    protected $modelClass;

    protected $searchModelClass;

    protected $parentKey;

    protected $perPage = 25;

    protected $actions = [
        'index' => [IndexAction::class],
        'create' => [CreateAction::class],
        'update' => [UpdateAction::class],
        'view' => [ViewAction::class],
        'delete' => [DeleteAction::class]
    ];

}