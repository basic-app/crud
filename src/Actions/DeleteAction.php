<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;
  
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Security\Exceptions\SecurityException;
use BasicApp\Crud\Events\BeforeDeleteEvent;

class DeleteAction extends \BasicApp\Action\BaseAction
{

    public $backUrl;

    public $beforeDeleteEvent;

    public $enableCsrfValidation = true;

    public function _remap($method, ...$params)
    {
        $backUrl = $this->backUrl;

        $beforeDeleteEvent = $this->beforeDeleteEvent;

        $enableCsrfValidation = $this->enableCsrfValidation;

        $return = function($method, ...$params) use ($backUrl, $beforeDeleteEvent, $enableCsrfValidation) {

            if ($this->enableCsrfValidation && ($this->request->method !== 'POST'))
            {
                if ($this->request->getGet(csrf_token()) != csrf_hash())
                {
                    throw SecurityException::forDisallowedAction();
                }
            }

            $model = model($this->modelClass, false);

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

            if ($beforeDeleteEvent)
            {
                $event = new BeforeDeleteEvent($entity);

                $event->trigger($beforeDeleteEvent);

                if ($event->result)
                {
                    return $event->result;
                }
            }

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