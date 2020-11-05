<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use BasicApp\Controller\ControllerInterface;

class IndexAction extends \BasicApp\Action\BaseAction
{

    protected $perPage;

    protected $view = 'index';

    protected $orderBy;

    protected $beforeFind;
    
    public function _remap($method, ...$params)
    {
        $view = $this->view;
        
        $perPage = $this->perPage;
        
        $beforeFind = $this->beforeFind;

        $orderBy = $this->orderBy;

        $return = function($method, ...$params) use ($view, $perPage, $beforeFind, $orderBy) {

            assert($this->modelClass ? true : false, __CLASS__ . '::modelClass');

            $model = model($this->modelClass);

            assert($model ? true : false, $this->modelClass);

            $query = $model->builder();

            $searchModel = null;

            $search = null;

            $searchErrors = [];

            $searchCustomErrors = [];

            if ($this->searchModelClass)
            {
                $searchModel = model($this->searchModelClass);

                assert($searchModel, $this->searchModelClass);

                $search = $searchModel->createEntity();

                $request = $this->request->getGet();

                if ($request)
                {
                    $searchModel->fillEntity($search, $request);

                    if ($searchModel->validate($search))
                    {
                        if ($searchModel->returnType == 'array')
                        {
                            $searchModel->entityApplyToQuery($search, $query);
                        }
                        else
                        {
                            $search->applyToQuery($query);
                        }
                    }
                    else
                    {
                        $query->where('1=0');

                        $searchErrors = (array) $searchModel->errors();
                    }
                }
            }

            if ($this->parentKey)
            {
                $parentId = $this->request->getGet('parentId');

                if ($parentId)
                {
                    $query->where($this->parentKey, $parentId);
                }
                else
                {
                    $query->where($this->parentKey, null);
                }
            }
            else
            {
                $parentId = null;
            }            

            if ($orderBy)
            {
                $query->orderBy($orderBy);
            }

            if ($beforeFind)
            {
                $this->$beforeFind($query);
            }

            if (!$perPage)
            {
                $perPage = $this->perPage;
            }

            if ($perPage)
            {
                $elements = $query->paginate($perPage);
            }
            else
            {
                $elements = $query->findAll();
            }

            $pager = $query->pager;

            return $this->render($view, [
                'model' => $model,
                'elements' => $elements,
                'pager' => $pager,
                'parentKey' => $this->parentKey,
                'parentId' => $parentId,
                'searchModel' => $searchModel,
                'search' => $search,
                'searchErrors' => $searchErrors,
                'searchCustomErrors' => $searchCustomErrors
            ]);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}