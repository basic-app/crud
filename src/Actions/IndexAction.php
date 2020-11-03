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

        $return = function($method, ...$params) use ($view, $perPage, $beforeFind, $orderBy)
        {
            $model = model($this->modelClass);

            $query = $model->builder();

            $searchModel = null;

            $search = [];

            $searchErrors = [];

            $searchCustomErrors = [];

            if ($this->searchModelClass)
            {
                $searchModel = model($this->searchModelClass);

                $searchReturnType = $searchModel->returnType;

                if ($searchReturnType != 'array')
                {
                    $search = new $searchReturnType;
                }

                $request = $this->request->getGet();

                if ($request)
                {
                    if (is_array($search))
                    {
                        foreach($request as $key => $value)
                        {
                            $search[$key] = $value;
                        }

                        // @todo set default attributes
                    }
                    else
                    {
                        $search->fill($request);
                    }

                    if ($searchModel->validate($search))
                    {
                        if (is_array($search))
                        {
                            $searchModel->applyToQuery($search, $query);
                        }
                        else
                        {
                            $search->applyToQuery($query);
                        }
                    }
                    else
                    {
                        $query->where('1=0');

                        $searchErrors = $searchModel ? (array) $searchModel->errors() : [];
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

            $params = [
                'model' => $model,
                'elements' => $elements,
                'pager' => $pager,
                'parentKey' => $this->parentKey,
                'parentId' => $parentId,
                'searchModel' => $searchModel,
                'search' => $search,
                'searchErrors' => $searchErrors,
                'searchCustomErrors' => $searchCustomErrors
            ];

            return $this->render($view, $params);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}