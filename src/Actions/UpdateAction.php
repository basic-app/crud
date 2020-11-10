<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use CodeIgniter\Exceptions\PageNotFoundException;
use BasicApp\Crud\Events\BeforeUpdateEvent;

class UpdateAction extends \BasicApp\Action\BaseAction
{

    protected $view = 'update';

    protected $backUrl;

    protected $beforeUpdateEvent;

    public function _remap($method, ...$params)
    {
        $view = $this->view;

        $backUrl = $this->backUrl;

        $beforeUpdateEvent = $this->beforeUpdateEvent;

        $return = function($method, ...$params) use ($view, $backUrl, $beforeUpdateEvent) {

            assert($this->formModelClass ? true : false, __CLASS__ . '::formModelClass');

            $errors = [];

            $customErrors = [];

            $model = model($this->formModelClass, false);

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
                
                if ($beforeUpdateEvent)
                {
                    $event = new BeforeUpdateEvent($entity, $hasChanged);

                    $event->trigger($beforeUpdateEvent);

                    if (is_array($entity))
                    {
                        $entity = $event->entity;
                    }

                    $hasChanged = $event->hasChanged;
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