<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

use Exception;

abstract class BaseDeleteAction extends ActionAbstract
{

    public function run(array $options = [])
    {
        $query = $this->createModel();

        $row = $this->findEntity();

        if ($this->request->getPost())
        {
            $primaryKey = $this->entityPrimaryKey($row);

            if (!$query->delete($primaryKey))
            {
                throw new Exception('Record is not deleted.');
            }
        }
        else
        {
            throw new PageNotFoundException;
        }

        return $this->redirectBack($this->returnUrl);
    }

}