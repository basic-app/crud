<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Events;

class BeforeInsertEvent extends \BasicApp\Event\BaseEvent
{

    public $model;

    public $entity;

    public $backUrl;

    public $customErrors = [];

    public function __construct($model, $entity, $backUrl, $customErrors)
    {
        parent::__construct();

        $this->model = $model;

        $this->entity = $entity;

        $this->backUrl = $backUrl;

        $this->customErrors = $customErrors;
    }

}