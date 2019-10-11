<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

abstract class BaseCreateAction extends ActionAbstract
{

    public $view;

    public function run(array $options = [])
    {
        $model = $this->createModel();

        $errors = [];

        $get = $this->request->getGet();

        $data = $this->createEntity($get);
        
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
            'model' => $model,
            'data' => $data,
            'errors' => $errors,
            'parentId' => $this->entityParentKey($data)
        ]);
    }

}