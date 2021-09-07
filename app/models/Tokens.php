<?php

class Tokens extends \Phalcon\Mvc\Model
{

   
    protected $id;

    
    protected $user_id;


    protected $game_id;

  
    protected $creation_date;


    protected $expiration_date;

    protected $used;

    protected $token_name;


    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setCurrDate($creation_date)
    {
        $this->creation_date = $creation_date;
        

        return $this;
    }


    public function setExpDate($expiration_date)
    {
        $this->expiration_date = $expiration_date;
        

        return $this;
    }


    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setGameId($game_id)
    {
        $this->game_id = $game_id;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

      public function setToken($token_name)
    {
        $this->token_name = $token_name;

        return $this;
    }


    
    public function getId()
    {
        return $this->id;
    }

      public function getUserId()
    {
        return $this->user_id;
    }

    public function getGameId()
    {
        return $this->game_id;
    }

    public function getCurrDate()
    {
        return $this->creation_date;
    }

    public function getExpDate()
    {
        return $this->expiration_date;
    }

    public function getUsed()
    {
        return $this->used;
    }

    public function getToken()
    {
        return $this->token_name;
    }

   

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("testdatabase");
        $this->setSource("tokens");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
     */
    public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function RegisterToken()
    {
        return [
            
            'user_id' => 'user_id',
            'game_id' => 'game_id',
            'creation_date' => 'creation_date',
            'expiration_date' => 'expiration_date',
            'used' => 'used',
            'token_name' => 'token_name'
        ];
    }

}
