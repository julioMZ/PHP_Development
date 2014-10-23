<?php

/**
 * 
 * Object representing a Collection of Buses.
 * @package     Objects
 * @category    Runner
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class BusFlee 
    implements Runner
{//---------------------------------------->> Class BusFlee
    
    /**
     *
     * Collection of Bus objects
     * @access  private
     * @var     array
     */
    private $_buses = array();
    
    /**
     * 
     * @param   array $busCollection Collection of Bus instances.
     * @throws  InvalidArgumentException
     */
    public function __construct( array $busCollection ) 
    {//-------------------->> __construct()
        
        if ( empty( $busCollection ) ) {//---------->> if $busCollection is empty
            throw new InvalidArgumentException( 'The bus collection is not allowed to be empty' );
        }//---------->> End if $busCollection is empty
        
        foreach ( $busCollection as $index => $bus ) {//---------->> foreach $bus
            
            if ( !$bus instanceof Runner ) {//---------->> if $bus is not a Bus object
                throw new InvalidArgumentException( "The {$index} index is not a Bus instance" );
            }//---------->> End if $bus is not a Bus object
            
            $this->_buses[] = $bus;
            
        }//---------->> End foreach $bus
        
    }//-------------------->> End __construct()
    
    /**
     * 
     * Puts every Bus on the Flee to run.
     */
    public function run()
    {//-------------------->> go()
        
        $result = array( "The Bus Flee is running!" );
        
        foreach ( $this->_buses as $bus ) {//---------->> foreach $bus
            $result[] = $bus->run();
        }//---------->> End foreach $bus
        
        return implode( "\n", $result );
        
    }//-------------------->> End go()
    
}//---------------------------------------->> End Class BusFlee