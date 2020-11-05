<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

class CreateAction extends \BasicApp\Action\BaseAction
{

    protected $view = 'create';

    protected $backUrl;

    public function _remap($method, ...$params)
    {
        $view = $this->view;

        $backUrl = $this->backUrl;

        $return = function($method, ...$params) use ($view, $backUrl) {

            assert($this->formModelClass ? true : false, __CLASS__ . '::formModelClass');

            $model = model($this->formModelClass);

            assert($model ? true : false, $this->formModelClass);

            $errors = [];

            $customErrors = [];

            $entity = $model->createEntity();

            if ($this->parentKey)
            {
                $parentId = $this->request->getGet('parentId');

                if ($parentId)
                {
                    $model->setEntityParentKey($entity, $parentId);
                }
            }

            $post = $this->request->getPost();

            if ($post)
            {
                $model->fillEntity($entity, $post);

                if ($model->save($entity))
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

            return $this->render($view, [
                'model' => $model,
                'entity' => $entity,
                'errors' => $errors,
                'customErrors' => $customErrors
            ]);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}