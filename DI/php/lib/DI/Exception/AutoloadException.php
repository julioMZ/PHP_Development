<?php

    //---- REQUIRE ----//
        require_once dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'Exception.php';
    //-----------------//

/**
 * 
 * Exception to manage errors from the DI_Autoload logic.
 * @package     DI
 * @subpackage  Exception
 * @category    Autoload Exception
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DI_Exception_AutoloadException 
    extends DI_Exception
{//---------------------------------------->> Class AutoloadException
    
}//---------------------------------------->> End Class AutoloadException