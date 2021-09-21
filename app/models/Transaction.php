<?php

class Transaction extends \Phalcon\Mvc\Model
{

   
    protected $id;

    protected $date_time;

    protected $amount;

    protected $cancelled;
    
    protected $type;

    public function setSession($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }


    public function setDate($date_time)
    {
        $this->date_time = $date_time;

        return $this;
    }




    
    public function getId()
    {
        return $this->id;
    }

    
    public function getDate_time()
    {
        return $this->date_time;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCancelled()
    {
        return $this->cancelled;
    }

    public function getType()
    {
        return $this->type;
    }


   

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("testdatabase");
        $this->setSource("transaction");
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
            
            'date_time' => 'date_time',
            'type' => 'type',
            'cancelled' => 'cancelled',
            'amount' => 'amount'

           
        ];
    }

}
