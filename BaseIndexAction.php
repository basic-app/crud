<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

abstract class BaseIndexAction extends Action
{

    protected $orderBy;

    protected $perPage = 25;

    protected $beforeFind;

    public function run(array $options = [])
    {
        $model = $this->createModel();

        $searchModel = $this->createSearchModel();

        if ($searchModel)
        {
            $search = $searchModel->createEntity($this->request->getGet());

            if ($searchModel->validate($search))
            {
                if (is_array($search) || !method_exists($search, 'applyToQuery'))
                {
                    $searchModel->applyToQuery($model, $search);
                }
                else
                {
                    $search->applyToQuery($model);
                }
            }
            else
            {
                $model->where('1=0');
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
                $model->where($parentKey, $parentId);
            }
            else
            {
                $model->where($parentKey, null);
            }
        }
        else
        {
            $parentId = null;
        }

        if ($this->orderBy)
        {
            $model->orderBy($this->orderBy);
        }

        if (is_callable($this->beforeFind))
        {
            call_user_func($this->beforeFind, $model);
        }

        $perPage = $this->perPage;

        if ($perPage)
        {
            $elements = $model->paginate($perPage);
        }
        else
        {
            $elements = $model->findAll();
        }

        return $this->render($this->view, [
            'model' => $model,
            'elements' => $elements,
            'pager' => $model->pager,
            'parentId' => $parentId,
            'parentKey' => $parentKey,
            'searchModel' => $searchModel,
            'search' => $search,
            'searchErrors' => $searchModel ? (array) $searchModel->errors() : []
        ]);
    }

}