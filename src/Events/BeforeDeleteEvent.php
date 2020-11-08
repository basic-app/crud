<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Events;

use Psr\Log\LoggerInterface;

class BeforeDeleteEvent extends \BasicApp\Event\BaseEvent
{

    public $entity;

    public $result;

    public function __construct(?LoggerInterface $logger, $entity)
    {
        parent::__construct($logger);

        $this->entity = $entity;
    }

}