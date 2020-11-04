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

            $model = model($this->formModelClass);

            $errors = [];

            $customErrors = [];

            if ($model->returnType == 'array')
            {
                $entity = [];
            }
            else
            {
                $modelClass = $model->returnType;

                $entity = new $modelClass;
            }

            $post = $this->request->getPost();

            if ($post)
            {
                if ($model->returnType == 'array')
                {
                    foreach($post as $key => $value)
                    {
                        if (!array_key_exists($key, $entity) || ($value != $entity[$key]))
                        {
                            $entity[$key] = $value;
                        }
                    }
                }
                else
                {
                    $entity->fill($post);
                }

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