<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Events;

class AfterLoadEvent extends \BasicApp\Event\BaseEvent
{

    public $model;

    public $entity;

    public function __construct($model, $entity)
    {
        parent::__construct();

        $this->model = $model;

        $this->entity = $entity;
    }

} 