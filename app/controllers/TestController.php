<?php
declare(strict_types=1);
use App\Library\WalletResponse;
use Phalcon\Forms\ElementInterface;
use Phalcon\Http\Request;

use Phalcon\Di;
use Phalcon\Session\Adapter\Noop;
use Phalcon\Session\Manager;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Session\Factory;
use Phalcon\Session\Bag as SessionBag;

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
    
    
   
        $response = new App\Library\WalletResponse();
        $action = json_decode($this->request->getRawBody(), true);
        $user_id = $action['user_id'];
        $game_id = $action['game_id'];

        $userData = Users::FindFirst(["id = {$user_id}"]);
        if( !($userData) ){
            $response->setError('User-ul nu a fost gasit!');
        }else {
             


            // token si date generator
             $token = new Tokens();
             $CurrentDate = new DateTime('Europe/Bucharest');
             $ExpDate = new DateTime('Europe/Bucharest');
             $ExpDate->modify('+5 minutes');         
             //database assign 
             $tokenData = Tokens::FindFirst(["user_id = {$user_id}"]);
             //$tokenData_game = Tokens::FindFirst(["game_id = {$game_id}"]);
               if( !($tokenData) ){

                     //random token
                       $random = new \Phalcon\Security\Random();
                       $bytes = $random->bytes();
                       $response->setData([
                        'token' => bin2hex($bytes)
                        ]);

                     $token->setToken($response->getData());
                     $token->setExpDate( $ExpDate->format('H:i:s'));
                     $token->setCurrDate( $CurrentDate->format('H:i:s'));
                     $token->setUserId($user_id);
                     $token->setGameId($game_id);

                     $token->save();
             }else {
	               
                   $timp = new DateTime('Europe/Bucharest');
                   if($tokenData->getExpDate() < $timp->format('H:i:s'))
                   {
                         
                          $tokenData->setUsed(1);
                          $tokenData->save();
                          $response->setError('Token expirat');                   
                   }
                   else
                   {
                    $tokenData->setUsed(0);
                    $tokenData->save();
                   }
                   

                  }
                

        }
        $response->sendResponse();

    }

    public function initAction()
    {
        
        $response = new App\Library\WalletResponse();
        $action = json_decode($this->request->getRawBody(), true);
        $user_id = $action['user_id'];
       

        $tokenData = Tokens::FindFirst(["user_id = {$user_id}"]);
        
        
        if( !($tokenData) ){
            $response->setError('User-ul nu a fost gasit!');
        }else {
            $tokenId = $tokenData->getId();
           
           $sessionData = Sesiune::FindFirst(["token_id = {$tokenId}"]);    
            

            if( !($sessionData) &&  ($tokenData->getUsed() == 0) ){
            
               $sesiune = new Sesiune();
               $timpul = new DateTime('Europe/Bucharest');

               $sesiune->setTokenId($tokenId);
               $sesiune->setStatus(1);
               $sesiune->setCurrDate( $timpul->format('H:i:s'));

               $sesiune->save();

               $tokenData->setUsed(1);
               $tokenData->save();

               //dupa ce salvez in database creez o sesiune cu datele respective
               $sessionData = Sesiune::FindFirst(["token_id = {$tokenId}"]);     

               $this->session->set('ID', $sessionData->getId());
               $this->session->set('Status', $sessionData->getStatus());
               $this->session->set('TokenId', $sessionData->getTokenId());
              
            }else
            {
                //sterg datele din sesiunea veche
                $this->session->remove('ID');
                $this->session->remove('Status');
                $this->session->remove('TokenId');

                //adaug datele sesiunii respective
                if($sessionData->getStatus() == 1)
                {
                    
                    $this->session->set('ID', $sessionData->getId());
                    $this->session->set('Status', $sessionData->getStatus());
                    $this->session->set('TokenId', $sessionData->getTokenId());
                }else
                {

                    $response->setError('Sesiune expirata');
                    $this->session->destroy();
                }

            }

        }
    

    }

    public function balanceAction()
    {

        $response = new App\Library\WalletResponse();
        $action = json_decode($this->request->getRawBody(), true);
        $ballance_value = $action['balance'];
       
     
        $sessionData = Sesiune::FindFirst(["id = {$this->session->get('ID')}"]);    
     

        
     
   

        if(! ($sessionData) )
        {

            $response->setError('Sesiune expirata');

        }else
        {
            if($sessionData->getStatus() == 1)
            {
                $tokenData = Tokens::FindFirst(["id = {$this->session->get('TokenId')}"]);
                $user_id = $tokenData->getUserId();
                $game_id = $tokenData->getGameId();
                $userData = Users::FindFirst(["id = {$user_id}"]);

                $gameData = Games::FindFirst(["id = {$game_id}"]);

                    if($userData)
                    {
                        if( $gameData )
                        {
                            $userData->setBalance($ballance_value);
                            $userData->save();
                            $this->session->set('Game', $gameData->getIdentifier());
                            $this->session->set('Balance', $userData->getBalance());

                            $Game = $this->session->get('Game');
                            $Balance = $this->session->get('Balance');

                            var_dump($Game);
                            var_dump($Balance);

                        }else{

                            $response->setError('Game not found');
                        }
                        

                    }else{
                        $response->setError('User not found');
                    }

            }else
            {
                $response->setError('Sesiune expirata');
            }
            

        }
        
    }

    

}

