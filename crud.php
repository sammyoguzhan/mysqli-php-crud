<?php
    date_default_timezone_set('US/Eastern');
    //THESE 2 FUNCTIONS(TESTLOG AND ERRORLOG) EXIST IN FUNCTIONS FILE TOO BUT IF I INCLUDE FUNCTIONS HERE, I AM HAVING AN ERROR IN HTML HEADERS OR OTHER FILES
    //BECAUSE FUNCTIONS FILE IS INCLUDED AFTER CRUD FILE IN OTHER FILE SO PHP THROWS A 'REDECLARE ' ERROR
    function ErrorLogCrud($error_data,$type){
        $newData='';
        $date =  date('Y-m-d');
        $time =  date('h:i:s');
        $log_file = 'C:/inetpub/wwwroot/atlantisweb.com/WebApp/system_logs/'.$type.'_error_log/'.$type.'_error_'.$date.'.txt';
        if(!file_exists($log_file)){
            $file_create =  fopen($log_file,"w+");
        }

        $location = getcwd();
        $initialData = file_get_contents($log_file);
        $newData .= "*********************************************\r\n";
        $newData .= "".$time."\r\n";
        $newData .= "*********************************************\r\n";
        $newData .= "LOCATION: ".$location."\r\n";
        $newData .= "\r\n".$error_data." \r\n".$initialData;
        file_put_contents($log_file,$newData);
    }
    function TestLogCrud($test_data,$type){
        $newData='';
        $date =  date('Y-m-d');
        $time =  date('h:i:s');
        $log_file = 'C:/inetpub/wwwroot/atlantisweb.com/WebApp/system_logs/test_log/test_log_'.$date.'.txt';
        if(!file_exists($log_file)){
            $file_create =  fopen($log_file,"w+");
        }

        $location = getcwd();
        $initialData = file_get_contents($log_file);
        $newData .= "*********************************************\r\n";
        $newData .= "".$time."\r\n";
        $newData .= "*********************************************\r\n";
        $newData .= "LOCATION: ".$location."\r\n";
        $newData .= "\r\n".$test_data." \r\n".$initialData;
        file_put_contents($log_file,$newData);

    }

    function GetSpecificFields($fields,$table_name,$condition,$write_log =false){
        
        include('config.php');
        $query_get = "SELECT ".$fields." FROM ".$table_name." ".$condition."";
        if($write_log == true){
            TestLogCrud($query_get,'crud');

        }

        $result = mysqli_query($conn, $query_get);
        
        if( $result === false ) {
            ErrorLogCrud($query_get,'crud');

            return false;
        } else {
            $rows_array=array();
            //$_SESSION['query'] = $query_get;
    
    
    
            if(!empty($result)){
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
               
           
                    $rows_array[] = $row;
                }
    
            }

            return $rows_array;
        }
        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //$result = GetSpecificFields("email","drivers"," WHERE email = '".$email."' AND is_active='1'");
        //echo "1st results driver_id is : ".$result[0]['driver_id'];
        sqlsrv_close( $conn );

    }

    function SQLExecute($query,$write_log = false){

        include('config.php');
        include('config.php');
        $query_get = "".$query."";
        if($write_log == true){
            TestLogCrud($query_get,'crud');
            

        }
        $result = mysqli_query($conn, $query_get);

        if( $result === false ) {
            ErrorLogCrud($query_get,'crud');
            return false;
        } else {
            $rows_array=array();
            //$_SESSION['query'] = $query_get;
            if(!empty($result)){
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            
                    $rows_array[] = $row;
                
                }
            }
            return $rows_array;
        }
        


        
        
        sqlsrv_close( $conn );
    }

    function GetColumns($table_name){
        include('config.php');
        /***************************************************************************
                            //GET COLUMN NAMES AND PREPARE VALUES
        ****************************************************************************/
        $column_names = "";
        $column_names_array=array();
        
        $values = "";

        $query_get_columns = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$table_name."'";
        //echo  $query_get_columns;



        if($write_log == true){
            TestLogCrud($query_get_columns,'crud');

        }


        $result = mysqli_query($conn, $query_get_columns);


        if( $result === false ) {
            ErrorLogCrud($query_get_columns,'crud');
            return false;
        } else {
            $i=0;
            while($row = mysqli_fetch_array($result, SQLSRV_FETCH_NUMERIC)){
            
                $column_names_array[$i] = $row[0];
                $i++;
            }

            return $column_names_array;
        }


        
        sqlsrv_close( $conn );
    }


    
    function Insert($table_name, $fields_array, $values_array,$write_log = false ){
        include('config.php');
        $set_values = "";
        $set_fields = "";
        
        for($i=0;$i<count($values_array);$i++){
            $set_values .="'".$values_array[$i]."',";


        }

        $values = rtrim($set_values,",");
        
        for($i=0;$i<count($fields_array);$i++){
            $set_fields .=$fields_array[$i].",";


        }

        $fields = rtrim($set_fields,",");

        
        

        /***************************************************************************
                                //INSERT PROCESS
        ****************************************************************************/
        $query_insert = "INSERT INTO ".$table_name." (".$fields.") VALUES (".$values.")";
        
        if($write_log == true){
            TestLogCrud($query_insert,'crud');

        }


        $result_insert = mysqli_query($conn, $query_insert);

        if( $result_insert === false ) {
            ErrorLogCrud($query_insert,'crud');
            return false;

        } else {
            return true;
        }
        

        
        //echo $query_insert;

        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //$fields_array=array("testname","lastname","21321321","3541354");
        //$test_values=array("testname","lastname","21321321","3541354");
        //Insert('drivers',$fields_array,$test_values);
        sqlsrv_close( $conn );
    }

    function Delete($table_name,$condition){

        include('config.php');
        $query_delete = "DELETE FROM ".$table_name." ".$condition."";
       
        if($write_log == true){
            TestLogCrud($query_delete,'crud');
        }


        $result_delete = mysqli_query($conn, $query_delete);

        if( $result_delete === false ) {
            ErrorLogCrud($query_delete,'crud');

            return false;

        } else {
            return true;
        }

        /***************************************************************************
                        //TEST
        ****************************************************************************/
         //Delete("drivers","WHERE first_name = 's' AND phone = 34");
         sqlsrv_close( $conn );
    }
    
    function Get($table_name,$condition,$write_log = false){
        include('config.php');
        $query_get = "SELECT * FROM ".$table_name." ".$condition."";

        if($write_log == true){
            TestLogCrud($query_get,'crud');

        }


        $result = mysqli_query($conn, $query_get);

        if( $result_delete === false ) {
            ErrorLogCrud($query_get,'crud');

            return false;

        } else {
            $rows_array=array();
            // $_SESSION['query'] = $query_get;
            
            if(!empty($result)){
                 while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                 
                     $rows_array[] = $row;
                 
                 }
             }
     
            // echo $rows_array;
            //var_dump($rows_array);
            
             return $rows_array;
        }


        
        

        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //$result = Get("drivers"," WHERE first_name = 's'");
        //echo "1st results driver_id is : ".$result[0]['driver_id'];
        sqlsrv_close( $conn );
    }

    function Update($table_name,$fields_array,$values_array,$condition,$write_log = false){
        include('config.php');
        /***************************************************************************
                                //UPDATE PROCESS
        ****************************************************************************/
        $query_update = "UPDATE ".$table_name." SET ";
        $set_option = "";

        for($i=0;$i<count($fields_array);$i++){
            $set_option .= $fields_array[$i]." = '".$values_array[$i]."',";


        }
        $set_option = rtrim($set_option,",");

        $query_update .=$set_option;
        $query_update .= " ".$condition ;

        if($write_log == true){
            TestLogCrud($query_update,'crud');

        }


        $result_update = mysqli_query($conn, $query_update);

        if( $result_update === false ) {
            ErrorLogCrud($query_update,'crud');

            return false;

        } else {
            return true;
        }
        

        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //
        
        //$test_fields=array("last_name");
        //$test_values=array("newwwww");
        //Update("drivers",$test_fields,$test_values,"WHERE phone ='1'");
        sqlsrv_close( $conn );
    }

    function FillDataTable($table,$primaryKey,$columns,$where,$method){
        // SQL server connection information
        $sql_details = array(
            'user' => 'sa',
            'pass' => 'Atlantis2020*',
            'db'   => 'Atlantis-db',
            'host' => '69.16.233.136'
        );
        


        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        * If you just want to use the basic configuration for DataTables with PHP
        * server-side, there is no need to edit below this line.
        */
        
        require( 'ssp.class.php' );
		
		if($method =='get'){
			  echo json_encode(
            SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$where)
        );
		} else {
			
			  echo json_encode(
            SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns,$where)
			);
		}
		
     
        
    }

    
/*DO TEST FAST BECAUSE TASKS ARE ALSO INCLUDING CRUD AND CONSTANTLY RUNNING IT*/
/* UPDATE GUIDS
    include('functions.php');
    $aa = CreateGuid();

    $test_fields=array("user_guid");
    $test_values=array("".$aa."");
    Update("users",$test_fields,$test_values,"WHERE user_id ='3'");
*/
//$is_order_assigned = GetSpecificFields("is_assigned,store_id","orders"," WHERE order_id = '70488'");
//echo var_dump($is_order_assigned);
/*
$result = GetColumns('products');

var_dump($result);*/
//FillDataTable('products');


/*CREATE DRIVER LOCATIONS

    
$result_drivers =GetSpecificFields("store_id","stores","");
//var_dump($result_drivers);
 for($i=0;$i<count($result_drivers);$i++){

     $created_date = date('Y-m-d H:i:s');

     $fields_array=array("store_id","car_driver_cota","motor_driver_cota","bike_driver_cota","store_order_limit_minute");
     $test_values=array("".$result_drivers[$i]['store_id']."","10","7","5","20",);
     Insert('store_settings',$fields_array,$test_values);

 }
*/


