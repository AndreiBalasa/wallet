<?php

class Games extends \Phalcon\Mvc\Model
{

   
    protected $id;

    protected $identifier;

   


    
     


    
    public function getId()
    {
        return $this->id;
    }

    
    public function getIdentifier()
    {
        return $this->identifier;
    }


   

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("testdatabase");
        $this->setSource("games");
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
            'identifier' => 'identifier'
           
        ];
    }

}
