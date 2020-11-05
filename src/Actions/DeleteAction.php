<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;
  
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Security\Exceptions\SecurityException;

class DeleteAction extends \BasicApp\Action\BaseAction
{

    public $backUrl;

    public function _remap($method, ...$params)
    {
        $backUrl = $this->backUrl;

        $return = function($method, ...$params) use ($backUrl) {

            $csrf = $this->request->getGet(csrf_token());

            if ($csrf != csrf_hash())
            {
                throw SecurityException::forDisallowedAction();
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