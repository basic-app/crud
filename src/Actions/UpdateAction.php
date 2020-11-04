<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use CodeIgniter\Exceptions\PageNotFoundException;

class UpdateAction extends \BasicApp\Action\BaseAction
{

    protected $view = 'update';

    protected $backUrl;

    public function _remap($method, ...$params)
    {
        $view = $this->view;

        $backUrl = $this->backUrl;

        $return = function($method, ...$params) use ($view, $backUrl) {

            $errors = [];

            $customErrors = [];

            $model = model($this->formModelClass);

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

            $post = $this->request->getPost();

            if ($post)
            {
                if ($model->returnType == 'array')
                {
                    $hasChanged = false;

                    foreach($post as $key => $value)
                    {
                        if (!array_key_exists($key, $entity) || ($value != $entity[$key]))
                        {
                            $entity[$key] = $value;

                            $hasChanged = true;
                        }
                    }
                }
                else
                {
                    $entity->fill($post);
                
                    $hasChanged = $entity->hasChanged();
                }

                if (!$hasChanged || $model->save($entity))
                {
                    if (!$backUrl)
                    {
                        $backUrl = $this->backUrl;
                    }

                    return $this->redirectBack($backUrl);
                }
                else
                {
                    $errors = (array) $model->errors();
                }
            }

            $parentId = null;

            if ($this->parentKey)
            {
                if ($model->returnType == 'array')
                {
                    $parentId = $entity[$this->parentKey];
                }
                else
                {
                    $parentId = $entity->{$this->parentKey};
                }
            }

            return $this->render($view, [
                'model' => $model,
                'entity' => $entity,
                'errors' => $errors,
                'customErrors' => $customErrors,
                'parentKey' => $this->parentKey,
                'parentId' => $parentId
            ]);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}