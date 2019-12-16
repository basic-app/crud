<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

abstract class BaseIndexAction extends Action
{

    const EVENT_BEFORE_FIND = 'onBeforeFind';

    protected $orderBy;

    protected $perPage = 25;

    protected $onBeforeFind = [];

    public function run(array $options = [])
    {
        $model = $this->createModel();

        $searchModel = $this->createSearchModel();

        if ($searchModel)
        {
            $search = $searchModel->createEntity();

            $search->fill($this->request->getGet());

            $searchModel::applyToQuery($search, $model);
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
        }
        else
        {
            $parentId = null;
        }

        if ($this->orderBy)
        {
            $model->orderBy($this->orderBy);
        }

        $this->trigger(static::EVENT_BEFORE_FIND, ['model' => $model]);

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
            'search' => $search
        ]);
    }

}