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

        $data = $this->findEntity($model);

        $post = $this->request->getPost();

        if ($post)
        {
            $data = $this->fillEntity($data, $post);

            if ($data instanceof \CodeIgniter\Entity)
            {
                if (!$data->hasChanged())
                {
                    return $this->redirectBack($this->returnUrl);
                }
            }

            if ($model->save($data))
            {
                return $this->redirectBack($this->returnUrl);
            }
        }

        return $this->render($this->view, [
            'errors' => (array) $model->errors(),
            'data' => $data,
            'model' => $model,
            'parentId' => $this->entityParentKey($data)
        ]);
    }

}