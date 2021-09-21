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
                     $token->setUsed(0);

                     $token->save();
             }else {
	               
                   $timp = new DateTime('Europe/Bucharest');
                   if($tokenData->getExpDate() < $timp->format('H:i:s'))
                   {
                          // daca a expirat timpul, setam tokenul ca si folosit si trimitem o eroare
                          $tokenData->setUsed(1);
                          $tokenData->save();
                          $response->setError('Token expirat');                   
                   }
                   else
                   {
                    //tokenul e setata ca si available
                    
                            if($tokenData->getUsed() == 1)
                            {
                                $response->setError('Token expirat');  
                            }else
                            {
                                var_dump($tokenData->getToken());
                            }

                    
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
        $Rawtoken = $action['token'];
       
        
        $tokenData = Tokens::FindFirst(["user_id = {$user_id}"]);  // caut tokenul in functie de user_id
        

        
        if( !($tokenData) ){
            $response->setError('User-ul nu a fost gasit!');
        }else {
            $tokenId = $tokenData->getId();
            $tokenss = $tokenData->getToken();
            if($Rawtoken == $tokenss)
            {

            $sessionData = Sesiune::FindFirst(["token_id = {$tokenId}"]);     
                

                if( !($sessionData)){   ///daca sesiunea nu exista creeam una noua
                if(($tokenData->getUsed() == 0)){  //daca tokenul este valid 
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

                        var_dump("Sesion id:");
                        var_dump($this->session->get('ID'));
                        var_dump("Token id:");
                        var_dump($this->session->get('TokenId'));
                }else{
                        $response->setError('Token Expirat');
                }
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

                        var_dump("Sesion id:");
                        var_dump($this->session->get('ID'));
                        var_dump("Token id:");
                        var_dump($this->session->get('TokenId'));

                    }else
                    {

                        $response->setError('Sesiune expirata');
                        $this->session->destroy();
                    }

                }
            }else
            {
                $response->setError('Token invalid');
            }
        }
            
    
        $response->sendResponse();

    }

    public function balanceAction()
    {

        $response = new App\Library\WalletResponse();
        $action = json_decode($this->request->getRawBody(), true);
        $session_id = $action['session_id'];
        $user_idRaw = $action['user_id'];
        $sessionData = new Sesiune();


      
        try{
            $sessionData = Sesiune::FindFirst(["id = {$session_id}"]);   
            $tokenValid = $sessionData->getTokenId();
            $tokenData = Tokens::FindFirst(["id = {$tokenValid}"]);
        }catch(\Exception $e){
            $response->setError('Eroare la preluarea datelor');
            $response->sendResponse();
        }
        
       
        if($sessionData->getStatus() == 0)
        {

            $response->setError('Sesiune expirata');

        }else
        {
            if($sessionData->getStatus() == 1)
            {
                
                $user_id = $tokenData->getUserId();
                $game_id = $tokenData->getGameId();

                if($user_id == $user_idRaw){
                        $userData = Users::FindFirst(["id = {$user_id}"]);

                        $gameData = Games::FindFirst(["id = {$game_id}"]);

                            if($userData)
                            {
                                if($gameData)
                                {
                                    
                                    $this->session->set('Game', $gameData->getIdentifier());
                                    $this->session->set('Balance', $userData->getBalance());

                                    $Game = $this->session->get('Game');
                                    $Balance = $this->session->get('Balance');

                                    var_dump("Joc:");
                                    var_dump($Game);
                                    var_dump("Balanta:");
                                    var_dump($Balance);

                                  //  $sessionData->setStatus(0);
                                 //    $sessionData->save();
                                 //   $this->session->destroy();

                                }else{

                                    $response->setError('Game not found');
                                }
                                

                            }else{
                                $response->setError('User not found');
                            }
                        }else
                            {
                                $response->setError('Session/ID invalid');
                            }

                 }else
                    {
                        $response->setError('Sesiune expirata');
                    }
            

        }
        
        $response->sendResponse();

    }

    

    public function betAction()
    {
        $response = new App\Library\WalletResponse();
        $action = json_decode($this->request->getRawBody(), true);
        $session_id = $action['session_id'];
        $round_id = $action['round_id'];
        $transaction_id = $action['transaction_id'];
        $amount = $action['amount'];
        //date din postman

        //date din database
        $sessionData = Sesiune::FindFirst(["id = {$session_id}"]);   
        
        $roundData = Round::FindFirst(["id = {$round_id}"]);
        $transactionData = Transaction::FindFirst(["id = {$transaction_id}"]);      
        

        if(!($sessionData)){
            $response->setError('Invalid session');
        }else
            {
            if($sessionData->getStatus() == 1)
            {
                if(($roundData) && ($transactionData))
                {
                    //preluam datele userului 
                    $token = $sessionData->getTokenId();
                    $tokenData = Tokens::FindFirst(["id = {$token}"]);
                    $user_id = $tokenData->getUserId();
                    $userData = Users::FindFirst(["id = {$user_id}"]);


                    if($amount > $userData->getBalance()){

                        $response->setError('Insuficiend founds');

                    }else
                    {   
                        //verific daca transactia din database corespunde cu datele din postman
                        if($amount != $transactionData->getAmount() || $transaction_id != $transactionData->getId() || $transactionData->getCancelled() == 1 ) 
                        {
                            $response->setError('Invalid transaction');
                        }else
                        {   
                            //verific daca runda din database corespunde cu datele din postman
                            if($round_id == $roundData->getId() ||  $session_id == $roundData->getSessionid())
                            {          
                                $suma = $userData->getBalance() - $amount;
                                $userData->setBalance($suma);
                                $userData->save();
                                var_dump($userData->getBalance());
                                }else
                                {
                                    $response->setError('Invalid round');
                                }
                            }
                    }
                    

                }else
                {
                    // setam round si transaction
                    var_dump("Round and Transaction created");  
                    $round = new Round();
                    $transaction= new Transaction();

                    //date pentru runda
                    if(!($roundData)){
                        $dateTime =new DateTime('Europe/Bucharest');
                        $round->setDate($dateTime->format('H:i:s'));
                        $round->setId($round_id);
                        $round->setClosed(0);
                        $round->setCancelled(0);
                        $round->setSession($sessionData->getId());
                        $round->save();
                    }

                    
                    //date pentru transaction
                    if(!($transactionData)){
                        
                        $dateTimetr =new DateTime('Europe/Bucharest');
                        $transaction->setDate($dateTimetr->format('H:i:s'));
                        $transaction->setAmount($amount);
                        $transaction->setCancelled(0);
                        $transaction->save();
                    }

                }

            }else
            {
                $response->setError('Sesiune expirata');
            }
        }



    }




}

