<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Events;

class BeforeDeleteEvent extends \BasicApp\Event\BaseEvent
{

    public $entity;

    public $result;

    public function __construct($entity)
    {
        parent::__construct();

        $this->entity = $entity;
    }

}