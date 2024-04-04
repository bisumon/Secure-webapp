<?php  
    try {
        $db_name = "Lovejoy";
        $db_user = "root";
        $db_password = "";
        $host = "localhost";

        $con = mysqli_connect($host,$db_user,$db_password,$db_name) or die("Unable to connect to database");
        
        // Function Clean String Values
        function escape($string)
        {
            global $con;
            return mysqli_real_escape_string($con,$string);
        }

        // Query Function
        function Query($query)
        {   
           
            global $con;
            return mysqli_query($con,$query); 
            
        }

        // Confirmation Function
        function confirm($result)
        {
            global $con;
            if(!$result)
            {
                die('Query Failed'.mysqli_error($con));
            }
        }

        // Fetch Data From Database
        function fetch_data($result)
        {
            return mysqli_fetch_assoc($result);
        }

        // Fetch all rows of data from database at once
        function fetch_all($result)
        {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        // Row Values From Database
        function row_count($count)
        {
            return mysqli_num_rows($count);
        }
    } catch(Exception $e) {
        echo $e;
    }
    

?>