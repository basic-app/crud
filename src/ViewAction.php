<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud;

class ViewAction extends \BasicApp\Action\BaseAction
{

    public $view;

    public function run(array $options = [])
    {
        $errors = [];

        $model = $this->createModel();

        $row = $this->findEntity($model);

        return $this->render($this->view, [
            'model' => $row,
            'parentId' => $this->entityParentKey($row)
        ]);
    }

}