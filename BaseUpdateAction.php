<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

abstract class BaseUpdateAction extends Action
{

    public $view;

    public function run(array $options = [])
    {
        $model = $this->createModel();

        $errors = [];

        $data = $this->findEntity();

        $post = $this->request->getPost();

        if ($post)
        {
            $data = $this->fillEntity($data, $post);

            if ($this->saveEntity($data, $errors))
            {
                return $this->redirectBack($this->returnUrl);
            }
        }

        return $this->render($this->view, [
            'errors' => $errors,
            'data' => $data,
            'model' => $model,
            'parentId' => $this->entityParentKey($data)
        ]);
    }

}