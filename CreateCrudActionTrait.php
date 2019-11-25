<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

use Config\Services;

trait CreateCrudActionTrait
{

    protected function createCrudAction(string $class, array $params = [])
    {
        $params['modelClass'] = $this->modelClass;

        $params['renderFunction'] = function(string $view, array $params = []) {

            if (method_exists($this, 'render'))
            {
                return $this->render($view, $params);
            }
            else
            {
                return view($view, $params);
            }
        };

        $params['redirectBackFunction'] = function($returnUrl) {

            if (method_exists($this, 'redirectBack'))
            {
                return $this->redirectBack($returnUrl);
            }
            else
            {
                return Services::response()->redirect($returnUrl);
            }
        };

        $params['returnUrl'] = property_exists($this, 'returnUrl') ? $this->returnUrl : null;

        return $class::factory($params);
    }

}