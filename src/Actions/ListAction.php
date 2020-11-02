<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

class ListAction extends \BasicApp\Action\BaseAction
{
    

    /*
    protected $orderBy;

    protected $perPage = 25;

    protected $beforeFind;

    public function run(array $options = [])
    {
        $model = $this->createModel();

        $query = $model->builder();

        $searchModel = $this->createSearchModel();

        if ($searchModel)
        {
            $search = $searchModel->createEntity($this->request->getGet());

            if ($searchModel->validate($search))
            {
                if (is_array($search) || !method_exists($search, 'applyToQuery'))
                {
                    $searchModel->applyToQuery($query, $search);
                }
                else
                {
                    $search->applyToQuery($query);
                }
            }
            else
            {
                $query->where('1=0');
            }
        }
        else
        {
            $search = null;
        }

        $parentKey = $this->parentKey;

        if ($parentKey)
        {
            $parentId = $this->request->getGet($this->parentKey, null);

            if ($parentId)
            {
                $query->where($parentKey, $parentId);
            }
            else
            {
                $query->where($parentKey, null);
            }
        }
        else
        {
            $parentId = null;
        }

        if ($this->orderBy)
        {
            $query->orderBy($this->orderBy);
        }

        if (is_callable($this->beforeFind))
        {
            call_user_func($this->beforeFind, $query);
        }

        $perPage = $this->perPage;

        if ($perPage)
        {
            $elements = $query->paginate($perPage);
        }
        else
        {
            $elements = $query->findAll();
        }

        return $this->render($this->view, [
            'model' => $model,
            'elements' => $elements,
            'pager' => $query->pager,
            'parentId' => $parentId,
            'parentKey' => $parentKey,
            'searchModel' => $searchModel,
            'search' => $search,
            'searchErrors' => $searchModel ? (array) $searchModel->errors() : []
        ]);
    }
    */

    public function _remap($method, ...$params)
    {
    }
   

}