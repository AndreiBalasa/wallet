<?php
declare(strict_types=1);
use App\Library\WalletResponse;
use Phalcon\Forms\ElementInterface;
use Phalcon\Http\Request;

class TestController extends ControllerBase
{

    public function initialize(){
        $action = json_decode($this->request->getRawBody(), true);
        if(!$action){
            die('Not found');
        }
    }

    public function indexAction()
    {
        die ('test index!');

       
    }

    public function launchAction()
    { 
    echo "mergeba";
    
    /**
    
        if($dupa > $acum)
        {
            echo "merge ba";
        }
        die();
      

        -----------------------------------------

         */

        $response = new App\Library\WalletResponse();
        $action = json_decode($this->request->getRawBody(), true);
        $user_id = $action['user_id'];
        $game_id = $action['game_id'];

        $userData = Users::FindFirst(["id = {$user_id}"]);
        if( !($userData) ){
            $response->setError('User-ul nu a fost gasit!');
        }else {
             //random token
             $random = new \Phalcon\Security\Random();
             $bytes = $random->bytes();

	        $response->setData([
            'token' => bin2hex($bytes)
             ]);


            // token si date generator
             $token = new Tokens();
             $CurrentDate = new DateTime('Europe/Bucharest');
             $ExpDate = new DateTime('Europe/Bucharest');
             $ExpDate->modify('+5 minutes');
            
            
             //database assign
             $tokenData = Tokens::FindFirst(["user_id = {$user_id}"]);
               if( !($tokenData) ){
                     $token->setToken($response->getData());
                     $token->setExpDate( $ExpDate->format('H:i:s'));
                     $token->setCurrDate( $CurrentDate->format('H:i:s'));
                     $token->setUserId($user_id);
                     $token->setGameId($game_id);

                     $token->save();
             }else {
	                var_dump("gasit");
                }
                

        }
        $response->sendResponse();

    }

    public function initAction()
    {
        die ('2!');
    }

    public function balanceAction()
    {
        die ('2!');
    }

    public function betAction()
    {
        die ('3!');
    }

    public function winAction()
    {
        die ('4!');
    }

    public function refundAction()
    {
        die ('5!');
    }

}

