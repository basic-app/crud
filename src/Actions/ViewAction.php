<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use CodeIgniter\Exceptions\PageNotFoundException;
use BasicApp\Crud\Events\AfterLoadEvent;

class ViewAction extends \BasicApp\Action\BaseAction
{

    protected $view = 'view';

    protected $afterLoadEvent;

    public function _remap($method, ...$params)
    {
        $view = $this->view;

        $afterLoadEvent = $this->afterLoadEvent;

        $return = function($method, ...$params) use ($view, $afterLoadEvent) {

            assert($this->modelClass ? true : false, __CLASS__ . '::modelClass');

            $model = model($this->modelClass, false);

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

            $event = new AfterLoadEvent($model, $entity);

            if ($afterLoadEvent)
            {
                $event->trigger($afterLoadEvent);
            }

            $parentId = $model->entityParentKey($event->entity);

            return $this->render($view, [
                'entity' => $event->entity,
                'model' => $model,
                'parentId' => $parentId,
                'parentKey' => $this->parentKey
            ]);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}