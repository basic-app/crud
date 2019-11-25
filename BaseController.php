<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
abstract class BaseController extends \CodeIgniter\Controller
{

    use CreateCrudActionTrait;
    
    use CrudTrait;

}