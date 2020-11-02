<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Crud\Actions;

class CreateAction extends \BasicApp\Action\BaseAction
{

    /*
    public $view;

    public function run(array $options = [])
    {
        $model = $this->createModel();

        $get = $this->request->getGet();

        $data = $this->createEntity($get);
        
        $post = $this->request->getPost();

        if ($post)
        {
            $data = $this->fillEntity($data, $post);

            if ($model->save($data))
            {
                return $this->redirectBack($this->returnUrl);
            }
        }

        $parentId = $this->entityParentKey($data);

        return $this->render($this->view, [
            'model' => $model,
            'data' => $data,
            'errors' => (array) $model->errors(),
            'parentId' => $parentId
        ]);
    }

    */

    public function _remap($method, ...$params)
    {
    }

}