<?php

/**
 * 
 * Object to represent a Person.
 * @package     Objects   
 * @category    Person 
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>     
 */
class Person 
{//---------------------------------------->> Class Person

    /**
     * 
     * Name of the Person.
     * @var     string
     * @access  private  
     */
    private $_name = '';
    
    /**
     *
     * Last Name of the Person.
     * @var     string
     * @access  private
     */
    private $_lastName = '';
    
    /**
     *
     * Age of the Person.
     * @var     int
     * @access  private
     */
    private $_age = 0;
    
    /**
     *
     * Object Constructor.
     * @param   string $name Name of the Person
     * @param   string $lastName Last Name of the Person
     * @param   int $age Age of the Person 
     */
    public function __construct( $name = '', $lastName = '', $age = 0 ) 
    {//-------------------->> __construct()
        $this->setName( $name );
        $this->setLastName( $lastName );
        $this->_age = (int) $age;
    }//-------------------->> End __construct()
    
    /**
     *
     * Get the Person's name.
     * @return  string
     */
    public function getName() 
    {//-------------------->> getName()
        return $this->_name;
    }//-------------------->> End getName()
    
    /**
     *
     * Set the Name of the Person.
     * @param   string $name Name of the Person.
     */
    public function setName( $name ) 
    {//-------------------->> setName()
        $this->_name = utf8_decode( (string) $name );
    }//-------------------->> End setName()

    /**
     *
     * Get the Person's last name.
     * @return  string 
     */
    public function getLastName() 
    {//-------------------->> getLastName()
        return $this->_lastName;
    }//-------------------->> End getLastName()

    /**
     *
     * Set the Last Name of the Person. 
     * @param   string $lastName Last Name of the Person.   
     */
    public function setLastName( $lastName ) 
    {//-------------------->> setLastName()
        $this->_lastName = utf8_decode( (string) $lastName );
    }//-------------------->> End setLastName()
    
    /**
     *
     * Get the Person's age.
     * @return  int    
     */
    public function getAge() 
    {//-------------------->> getAge()
        return $this->_age;
    }//-------------------->> End getAge()
    
    /**
     *
     * Set the age of the Person.
     * @param   int $age Age of the Person.  
     */
    public function setAge( $age ) 
    {//-------------------->> setAge()
        $this->_age = (int) $age;
    }//-------------------->> End setAge()
    
    /**
     * 
     * Retrives an introductory message according to the current object state.
     * @return  string
     */
    public function introduce()
    {//-------------------->> introduce()
        return "Hi! My name is {$this->_name} {$this->_lastName} and I'm {$this->_age} years old.";
    }//-------------------->> End introduce()
    
}//---------------------------------------->> End Class Person