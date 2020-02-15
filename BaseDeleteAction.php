<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

use Exception;
use BasicApp\Exceptions\ForbiddenException;

abstract class BaseDeleteAction extends Action
{

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

}