<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

use CodeIgniter\Model;
use CodeIgniter\Entity;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Database\Exceptions\DataException;
use Exception;
use Config\Database;

abstract class BaseAction implements ActionInterface
{

    protected $owner;

    public $request;

    public $db;

    public $modelClass;

    public $searchModelClass;

	public $controller;

    public $returnUrl;

	public $onCreateModel = [];

	public $onCreateSearchModel = [];

	public $renderFunction;

    public $redirectBackFunction;

    public $parentKey;

    public $primaryKey = 'id';

	const EVENT_CREATE_MODEL = 'onCreateModel';

	const EVENT_CREATE_SEARCH_MODEL = 'onCreateSearchModel';

    public function __construct()
    {
        $this->db = Database::connect();

        $this->request = service('request');
    }

    public static function factory(array $params = [])
    {
        $class = get_called_class();

        $return = new $class;

        foreach($params as $key => $value)
        {
            $return->$key = $value;
        }

        return $return;
    }

	/**
	 * A simple event trigger for Action Events that allows additional
	 * data manipulation within the action.
	 *
	 * @param string $event
	 * @param array  $data
	 *
	 * @return mixed
	 * @throws \CodeIgniter\Database\Exceptions\DataException
	 */
	public function trigger(string $event, array $data)
	{
		if (!isset($this->{$event}) || empty($this->{$event}))
		{
			return $data;
		}

		foreach ($this->{$event} as $callback)
		{
			if (!method_exists($this, $callback))
			{
				throw DataException::forInvalidMethodTriggered($callback);
			}

			$data = $this->{$callback}($data);
		}

		return $data;
	}

	public function createModel()
	{
        $className = $this->modelClass;

		$model = $className::factory();

		$this->trigger(static::EVENT_CREATE_MODEL, ['model' => $model]);

		return $model;
	}

	public function createSearchModel()
	{
        $className = $this->searchModelClass;

        if (!$className)
        {
            return null;
        }

		$searchModel = $className::factory();

		$this->trigger(static::EVENT_CREATE_SEARCH_MODEL, ['searchModel' => $searchModel]);

		return $searchModel;
	}

	public function render(string $view, array $params = []) : string
	{
		$function = $this->renderFunction;

		return $function($view, $params);
	}

    public function redirectBack($defaultUrl)
    {        
        $function = $this->redirectBackFunction;

        return $function($defaultUrl);
    }

    protected function fillEntity($entity, array $values)
    {
        $modelClass = $this->modelClass;

        $allowedFields = $modelClass::defaultProperty('allowedFields');

        foreach($values as $key => $value)
        {
            if (array_search($key, $allowedFields) === false)
            {
                unset($values[$key]);
            }
        }

        if (is_array($entity))
        {
            foreach($values as $key => $value)
            {
                $entity[$key] = $value;
            }
        }
        else
        {
            $entity->fill($values);
        }

        return $entity;
    }

    protected function entityPrimaryKey($row)
    {
        if ($row instanceof Entity)
        {
            return $row->getPrimaryKey();
        }

        $model = $this->createModel();

        if (($model instanceof \CodeIgniter\Model) && ($model->returnType == 'array'))
        {
            $key = $model->primaryKey;
            
            return $row[$key];
        }

        throw new Exception('Unknown primary key.');
    }

    protected function entityParentKey($row, bool $throwException = false)
    {
        $parentKey = $this->parentKey;

        $model = $this->createModel();

        if ($model instanceof \CodeIgniter\Model)
        {
            if ($model->returnType == 'array')
            {
                if (array_key_exists($parentKey, $row))
                {
                    return $row[$parentKey];
                }
            }
            else
            {
                return $row->$parentKey;
            }
        }

        return null;
    }

    protected function createEntity(array $params = [])
    {
        $modelClass = $this->modelClass;

        $return = $modelClass::createEntity();

        $return = $this->fillEntity($return, $params);

        return $return;
    }

    protected function findEntity(Model $model, bool $throwException = true)
    {
        $id = $this->request->getGet($this->primaryKey);

        if (!$id)
        {
            throw new PageNotFoundException;
        }

        $row = $model->find($id);

        if (!$row)
        {
            if (!$throwException)
            {
                return null;
            }

            throw new PageNotFoundException;
        }

        return $row;
    }

}