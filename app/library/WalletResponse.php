<?php 
    namespace App\Library;

    use Phalcon\Http\Response;



    class WalletResponse extends Response{

        CONST STATUS_OK = 1;
        CONST STATUS_ERROR = 0;

        private $status = self::STATUS_OK; 
        private $error_message = null;
        private $data = [];

        public function __construct(){
            parent::__construct();

        }

        public function setStatus(int $status){
            $this->status = $status;
        }

        public function setError(string $error_message){
            $this->error_message = $error_message;
            $this->setStatus(self::STATUS_ERROR);
            $this->sendResponse();
        }

        public function setData(array $data){
            $this->data = $data;
        }

         public function getData(){
            return $this->data['token'];
        }

        public function sendResponse(){

            $headers  = $this->getHeaders();
            $headers->set('Content-Type', 'application/json');
            $this->setHeaders($headers);

            $responseData = [
                "status" => $this->status
            ];
            
            if( $this->status === self::STATUS_OK){
                $this->setStatusCode(200, 'Success');
                $responseData += $this->data;
            }else{
                $this->setStatusCode(401, 'Not allowed');
                $responseData += ['error_message' => $this->error_message];
            }

            

            $this->setContent(json_encode($responseData));
            $this->send();
            die();
        }

    }