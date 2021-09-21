<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

use App\Forms\RegisterForm;
use Phalcon\Flash\Direct as FlashDirect;



class SignupController extends Controller
{

    public function indexAction()
    {
        
    }

      public function registerAction()
    {
       $user = new Users();
       $request = new Request();


      

       if($this->request->isPost())
       {
        

            $name = $this->request->getPost('name',['trim','string']);
            $email = $this->request->getPost('email',['trim','email']);
            $password =$this->request->getPost('password');
           

             
             $user->assign(
                [
                "name" =>$name,
                "email"=>$email,
                "password"=>$this->security->hash($password),
                "balance" => 100
                 ],
                [
                "name",
                "email",
                 "password",
                 "balance"
                ]
            );

           // $user->password = $this->security->hash($password);
           
       }

       


        


        // Store and check for errors
        $success = $user->save();

        // passing the result to the view
        $this->view->success = $success;

        if ($success) {
            $message = "Thanks for registering!";
        } else {
            $message = "Sorry, the following problems were generated:<br>"
                     . implode('<br>', $user->getMessages());
        }

        // passing a message to the view
        $this->view->message = $message;
    }
}