<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

//use Exception;
//use BasicApp\Exceptions\ForbiddenException;
//      if ($this->request->getPost())
//        {
//          throw new ForbiddenException;
  
use CodeIgniter\Exceptions\PageNotFoundException;

class DeleteAction extends \BasicApp\Action\BaseAction
{

    public $backUrl;

    /*
    public function run(array $options = [])
    {
        $model = $this->createModel();

        $data = $this->findEntity($model);

        if ($this->request->getPost())
        {
            $id = $model::entityPrimaryKey($data);

            if (!$id)
            {
                throw new Exception('Primary key not defined.');
            }

            if (!$model->delete($id))
            {
                throw new Exception('Entity not deleted.');
            }
        }
        else
        {
            throw new ForbiddenException;
        }

        return $this->redirectBack($this->returnUrl);
    }
    */

    public function _remap($method, ...$params)
    {
        $backUrl = $this->backUrl;

        $return = function($method, ...$params) use ($backUrl) {

            $model = model($this->modelClass);

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

            //if ($this->parentKey)
            //{
            //    $root = $entity;

            //    $childrens = $model->builder()->where($this->parentKey, $root->id)->findAll();

            //    foreach($childrens as $child)
            //    {
            //        $model->delete($model->entityPrimaryKey($child));
            //    }
            //}

            $model->deleteEntity($entity);

            if (!$backUrl)
            {
                $backUrl = $this->backUrl;
            }

            return $this->redirectBack($backUrl);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}