[ {

    "DI_Config": {
        
        "paths"     : {
            "contextPath"   : "C:\\\\xampp\\htdocs\\projects\\PHP_Development\\DI\\",
            "packages"      : { "OBJECTS" : "php\\lib\\Objects\\" }
        },
        
        "objects"   : {
            
            "Julio"     : {
                "classPath"     : "Person.php",
                "constArg"      : [ "Julio", "Mora" ],
                "setPropery"    : {
                  "age"         : 30  
                },
                "scope"         : "Request"
            },
            
            "Miguel"    : {
                "classPath"     : "Person.php",
                "setProperty"   : { 
                    "name"      : "Miguel", 
                    "lastName"  : "De Olarte", 
                    "age"       : 36
                },
                "scope"         : "Request"
            },
            
            "Bus1"       : {
                "classPath"     : "Bus.php",
                "constArg"      : [ { "ref" : "Miguel" } ],
                "scope"         : "Request"
            },
            
            "Bus2"       : {
                "classPath"     : "Bus.php",
                "constArg"      : [ { "ref" : "Julio" } ],
                "scope"         : "Request"
            },
            
            "BusFlee"   : {
                "classPath"     : "BusFlee.php",
                "constArg"      : [ [ { "ref" : "Bus1" }, { "ref" : "Bus2" } ] ],
                "scope"         : "Session"
            }
            
        }
        
    }

} ]