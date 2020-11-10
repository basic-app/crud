<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Events;

class BeforeDeleteEvent extends \BasicApp\Event\BaseEvent
{

    public $model;

    public $entity;

    public $backUrl;

    public function __construct($model, $entity, $backUrl)
    {
        parent::__construct();

        $this->model = $model;

        $this->entity = $entity;

        $this->backUrl = $backUrl;
    }

}