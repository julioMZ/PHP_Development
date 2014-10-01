<!DOCTYPE html>
<html>
    
    <head>
        <title>Cache API Demo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        
        <h1>Cache <?php echo $type; ?> API Demo</h1>
        
        <h2>Simple Data Example</h2>
        
        <table border="1">
            <tr>
                <td>Current Data</td>
                <td><?php echo $data; ?></td>
            </tr>
            <tr>
                <td>Current Cache File Content</td>
                <td><?php echo $cacheFileData; ?></td>
            </tr>
            <tr>
                <td>Cache Data</td>
                <td><?php echo $cacheData; ?></td>
            </tr>
        </table>
        
        <h2>Serialized Data Example</h2>
        
        <table border="1">
            <tr>
                <td>Current Cache File Content</td>
                <td>
                    <pre><?php var_dump( $serializedCacheFileData ); ?></pre>
                </td>
            </tr>
            <tr>
                <td>Cache Data</td>
                <td>
                    <pre><?php var_dump( $serializedCacheData ); ?></pre>
                </td>
            </tr>
        </table>
        
    </body>
    
</html>