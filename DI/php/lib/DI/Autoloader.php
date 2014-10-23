<?php

    //---- REQUIRE DEPENDENCIES ----//
        require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Exception' . DIRECTORY_SEPARATOR . 'AutoloadException.php';
    //-----------------------------//
        
/**
 * 
 * Class with all the logic to register paths into the PHP's include paths and
 * to autoload Classes by its names using PERT/Zend convenion.
 * @package     DI 
 * @category    Autoloader
 * @version     1.0
 * @author      Julio Mora <julio.mora.zamora@gmail.com>
 */
class DI_Autoloader 
{//---------------------------------------->> Class Autoloader
    
    /**
     *
     * Assosiative Array with all the paths setted in PHP's
     * include paths.
     * @static
     * @var     array
     * @access  private
     */
    private static $_paths = array();
    
    /**
     * 
     * Set paths as PHP's Include Paths.
     * @static
     * @param   array $includePaths Assosiative arrayy containing the next keys:
     * <table border="1">
     *  <tr>
     *      <th>Key</th>
     *      <th>Type</th>
     *      <th>Description</th>
     *  <tr>
     *      <td>contextPath</td>
     *      <td>string</td>
     *      <td>Base Path of the Packages</td>
     *  <tr>
     *      <td>packages</td>
     *      <td>array</td>
     *      <td>
     *          Assosiative Array containing all the package paths relative to 
     *          the recived $contextPath where, the key will be the ID of the package
     *          (in orderr to be used to be retrived by DI_Autoloader::getPackagePathById())
     *          and the value the relative path where the package is located inside the
     *          contex path.
     *      </td>
     *  </tr>
     * </table>
     * @throws  DI_Exception_AppContextException   
     */
    public static function setIncludePaths( array $includePaths )
    {//-------------------->> _setIncludePaths()
        
        $contextPath = '';
        
        if ( isset ( $includePaths[ 'contextPath' ] ) && !empty( $includePaths[ 'contextPath' ] ) ) {//---------->> if contextPath is defined
            
            $contextPath = $includePaths[ 'contextPath' ];
            
            if ( !is_dir( $contextPath ) || !is_readable( $contextPath ) ) {//---------->> if invalid $contextPath
                throw new DI_Exception_AutoloadException( "Invalid Context Path {$contextPath}" );
            }//---------->> End if invalid $contextPath
            
            self::$_paths[ 'contexPath' ] = $contextPath;
            
        }//---------->> End if contextPath is defined
        
        $packages = ( isset( $includePaths[ 'packages' ] ) ) ? (array) $includePaths[ 'packages' ] : array();
        self::_setPackagesPaths( $packages );
        
        set_include_path( 
            get_include_path() . PATH_SEPARATOR . 
            implode( PATH_SEPARATOR, self::$_paths ) 
        );
        
    }//-------------------->> End _setIncludePaths()
    
    /**
     * 
     * This method builds and checks that any package exists, as directories, inside
     * the context path directory.
     * <p>
     *      If the package path exists, it will be checked to not be present on current
     *      get_include_path(). If its not present it will be registered inside the static
     *      DI_Autoloader::$_paths assosiative array.
     * </p>
     * @static
     * @param   array $packages Assosiative Array containing all the package paths relative to the recived $contextPath
     * @throws  DI_Exception_AutoloadException
     */
    private static function _setPackagesPaths( array $packages )
    {//-------------------->> _setPackagesPaths()
        
        $currentIncludePaths = explode( PATH_SEPARATOR, get_include_path() );
        $contextPath = self::$_paths[ 'contexPath' ];
        
        foreach ( $packages as $packageID => $package ) {//---------->> foreach package
            
            $packagePath = "{$contextPath}{$package}";
            
            if ( !is_dir( $packagePath ) || !is_readable( $packagePath ) ) {//---------->> if invalid $contextPath
                throw new DI_Exception_AutoloadException( "Invalid Package Path {$packagePath}" );
            }//---------->> End if invalid $contextPath
            
            if ( !in_array( $packagePath, $currentIncludePaths ) ) {//---------->> if path not currently on include paths
                self::$_paths[ $packageID ] = $packagePath;
            }//---------->> End if path not currently on include paths
            
        }//---------->> End foreach package
        
    }//-------------------->> End _setPackagesPaths()
    
    /**
     * 
     * Retrives the full path of a package by its key ID.
     * @static
     * @param   mixed $packageID
     * @return  string|null
     */
    public static function getPackagePathById( $packageID )
    {//-------------------->> getPackagePathById()
        return ( isset( self::$_paths[ $packageID ] ) ) ? self::$_paths[ $packageID ] : null;
    }//-------------------->> End getPackagePathById()
    
    /**
     * 
     * Method that loads dynamically a Class file by its name.
     * <p>
     *  A PERT convention is used so each "<i>_</i>" caracter in the class name
     *  will be remplaced by the <i>DIRECTORY_SEPARATOR</i> value.
     * </p>
     * @static
     * @param   string $className Name of the class to be required.
     */
    public static function load( $className )
    {//-------------------->> load()
        
        $className = str_replace( '_', DIRECTORY_SEPARATOR, $className ) . '.php';
        
        //---- REQUIRE ----//
            require_once $className;
        //-----------------//
            
    }//-------------------->> End load()
    
    /**
     * 
     * Registres into the <i>spl_autoload_register</i> function the <i>DI_Autoloader::load()</i> method.
     * <p>
     *  If the <i>$includePaths</i> is a non empty array, the <i>DI_Autoloader::setIncludePaths()</i>
     *  will be called internally before the <i>spl_autoload_register</i> invokation.
     * </p>
     * @static
     * @param   array $includePaths Assosiative array describing contextPath and packages paths config.
     * @return  boolean
     */
    public static function registerAutoload( array $includePaths = array() )
    {//-------------------->> registerAutoload()
        
        if ( !empty( $includePaths ) ) {//---------->> if $includePaths
            self::setIncludePaths( $includePaths );
        }//---------->> End if $includePaths
        
        return spl_autoload_register( array( __CLASS__, 'load' ) );
        
    }//-------------------->> End registerAutoload()
    
}//---------------------------------------->> End Class Autoloader