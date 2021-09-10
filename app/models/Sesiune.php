<?php

class Sesiune extends \Phalcon\Mvc\Model
{

   
    protected $id;

    protected $token_id;

    protected $creation_date;

    protected $status;

   


    public function setTokenId($token_id)
    {
        $this->token_id = $token_id;

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



    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

     


    
    public function getId()
    {
        return $this->id;
    }

      public function getTokenId()
    {
        return $this->token_id;
    }


    public function getCurrDate()
    {
        return $this->creation_date;
    }

    public function getStatus()
    {
        return $this->status;
    }


   

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("testdatabase");
        $this->setSource("sesiune");
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
            
            'creation_date' => 'creation_date',
            'status' => 'status',
            'token_id' => 'token_id'
        ];
    }

}
