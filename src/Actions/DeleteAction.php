<?php
/**
 * @author Basic App Dev Team <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

use Exception;
use CodeIgniter\HTTP\RedirectResponse;
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

            if ($this->enableCsrfValidation && ($this->request->getMethod() !== 'POST'))
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

            if (!$backUrl)
            {
                $backUrl = $this->backUrl;
            }

            $event = new BeforeDeleteEvent($model, $entity, $backUrl);

            if ($beforeDeleteEvent)
            {
                $event->trigger($beforeDeleteEvent);
            }

            assert($model->deleteEntity($event->entity) ? true : false, get_class($model) . '::deleteEntity');

            return $this->redirectBack($event->backUrl);
        };

        $return = $return->bindTo($this->controller, get_class($this->controller));

        return $return;
    }

}