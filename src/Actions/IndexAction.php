<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use BasicApp\Controller\ControllerInterface;
use BasicApp\Crud\Events\BeforeFindEvent;

class IndexAction extends \BasicApp\Action\BaseAction
{

    protected $perPage;

    protected $view = 'index';

    protected $orderBy;

    protected $beforeFindEvent;
    
    public function _remap($method, ...$params)
    {
        $view = $this->view;
        
        $perPage = $this->perPage;
        
        $beforeFindEvent = $this->beforeFindEvent;

        $orderBy = $this->orderBy;

        $return = function($method, ...$params) use ($view, $perPage, $orderBy, $beforeFindEvent) {

            assert($this->modelClass ? true : false, __CLASS__ . '::modelClass');

            $model = model($this->modelClass, false);

            assert($model ? true : false, $this->modelClass);

            $query = $model->builder();

            $searchModel = null;

            $search = null;

            $searchErrors = [];

            $searchCustomErrors = [];

            if ($this->searchModelClass)
            {
                $searchModel = model($this->searchModelClass, false);

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

            if (!$orderBy)
            {
                $orderBy = $this->orderBy;
            }

            if (!$perPage)
            {
                $perPage = $this->perPage;
            }

            $event = new BeforeFindEvent($model, $query, $perPage, $orderBy);

            if ($beforeFindEvent)
            {
                $event->trigger($beforeFindEvent);
            }

            if ($event->orderBy)
            {
                $query->orderBy($event->orderBy);
            }

            if ($event->perPage)
            {
                $elements = $query->paginate($event->perPage);
            }
            else
            {
                $elements = $query->findAll();
            }

            return $this->render($view, [
                'model' => $model,
                'elements' => $elements,
                'pager' => $query->pager,
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