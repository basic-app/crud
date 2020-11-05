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

            assert($this->formModelClass ? true : false, __CLASS__ . '::formModelClass');

            $errors = [];

            $customErrors = [];

            $model = model($this->formModelClass);

            assert($model ? true : false, $this->formModelClass);

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
                $hasChanged = $model->fillEntity($entity, $post);

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

            $parentId = $model->entityParentKey($entity);

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