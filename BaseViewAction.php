<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

abstract class BaseViewAction extends Action
{

    public $view;

    public function run(array $options = [])
    {
        $errors = [];

        $row = $this->findEntity();

        return $this->render($this->view, [
            'model' => $row,
            'parentId' => $this->entityParentKey($row)
        ]);
    }

}