<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Events;

class BeforeFindEvent extends \BasicApp\Event\BaseEvent
{

    public $model;

    public $query;

    public $perPage;

    public $orderBy;

    public function __construct($model, $query, $perPage, $orderBy)
    {
        parent::__construct();

        $this->model = $model;

        $this->query = $query;

        $this->perPage = $perPage;

        $this->orderBy = $orderBy;
    }

}