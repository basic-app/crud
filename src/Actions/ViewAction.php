<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use CodeIgniter\Exceptions\PageNotFoundException;

class ViewAction extends \BasicApp\Action\BaseAction
{

    protected $view = 'view';

    public function _remap($method, ...$params)
    {
        $view = $this->view;

        $return = function($method, ...$params) use ($view) {

            assert($this->modelClass ? true : false, __CLASS__ . '::modelClass');

            $model = model($this->modelClass);

            assert($this->modelClass ? true : false, $this->modelClass);

            $id = $this->request->getGet('id');

            if (!$id)
            {
                throw PageNotFoundException::forPageNotFound();
            }

            $entity = $model->find($id);

            if (!$entity)
            {
                throw PageNotFoundException::forPageNotFound();
            }

            $parentId = $model->entityParentKey($entity);

            return $this->render($view, [
                'entity' => $entity,
                'model' => $model,
                'parentId' => $parentId,
                'parentKey' => $this->parentKey
            ]);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }
    

}