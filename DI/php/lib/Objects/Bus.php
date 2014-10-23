<?php

/**
 * 
 * Object representing a Bus.
 * @package     Objects
 * @category    Runner
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class Bus 
    implements Runner
{//---------------------------------------->> Class Bus

    /**
     *
     * Operator of this Bus.
     * @var     Person
     * @access  private
     */
    private $_operator;
    
    /**
     *
     * Object Constructor
     * @param   Person $operator Operator of this Bus Instance.
     */
    public function __construct( Person $operator ) 
    {//-------------------->> __construct()
        $this->_operator = $operator;
    }//-------------------->> End __construct()
    
    /**
     *
     * Sets the Person who will operate this bus instance.
     * @param   Person $operator Operator of this Bus Instance.
     */
    public function setOperator( Person $operator ) 
    {//-------------------->> setOperator()
        $this->_operator = $operator;
    }//-------------------->> End setOperator()
    
    /**
     * 
     * Retrieves the Person who is operating this bus instance.
     * @return  Person
     */
    public function getOperator()
    {//-------------------->> getOperator()
        return $this->_operator;
    }//-------------------->> End getOperator()
    
    /**
     * 
     * Method to put the Bus to run.
     */
    public function run()
    {//-------------------->> run()
        return "The Operator has turned on the Bus, introduces him/her self saying: \"{$this->_operator->introduce()}\" and the Bus is running!";
    }//-------------------->> End run()
    
}//---------------------------------------->> End Class Bus