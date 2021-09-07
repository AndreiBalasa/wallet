<?php
declare(strict_types=1);

use Phalcon\Http\Request;

// use form

use App\Forms\LoginForm;

class UserController extends \Phalcon\Mvc\Controller
{

    public function loginAction()
    {

      
        $this->tag->setTitle('Phalcon :: login');
        //login form
        $this->view->form = new LoginForm();

    }



}

