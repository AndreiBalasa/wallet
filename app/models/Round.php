<?php

class Round extends \Phalcon\Mvc\Model
{

   
    protected $id;
    

    protected $date_time;

    protected $closed;

    protected $cancelled;
    
    protected $session_id;

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setSession($session_id)
    {
        $this->session_id = $session_id;

        return $this;
    }

    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;

        return $this;
    }

    public function setClosed($closed)
    {
        $this->closed = $closed;

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

    public function getClosed()
    {
        return $this->closed;
    }

    public function getCancelled()
    {
        return $this->cancelled;
    }

    public function getSessionid()
    {
        return $this->session_id;
    }


   

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("testdatabase");
        $this->setSource("round");
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
            'closed' => 'closed',
            'cancelled' => 'cancelled',
            'session_id' => 'session_id'

           
        ];
    }

}
