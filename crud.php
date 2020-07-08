<?php
    date_default_timezone_set('US/Eastern');
    function Get($fields,$table_name,$condition){
        include('config.php');
        $query_get = "SELECT ".$fields." FROM ".$table_name." ".$condition."";
        $result = mysqli_query($conn, $query_get);
        $rows_array=array();
        $_SESSION['query'] = $query_get;
        $rows_array= array();
        if(!empty($result)){
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $rows_array[] = $row;
            }
        }
        return $rows_array;
        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //$result = Get("email","drivers"," WHERE email = '".$email."' AND is_active='1'");
        //echo "1st results driver_id is : ".$result[0]['driver_id'];
        mysqli_close( $conn );
    }
	

    function SQLExecute($query){
        include('config.php');
        $query_get = "".$query."";
        $result = mysqli_query($conn, $query_get);
        $rows_array=array();
        $_SESSION['query'] = $query_get;
        while($row = mysqli_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $rows_array[] = $row;
        }
        return $rows_array;
        mysqli_close( $conn );
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
        $result = mysqli_query($conn, $query_get_columns);
        $i=0;
        while($row = mysqli_fetch_array($result, SQLSRV_FETCH_NUMERIC)){
            $column_names_array[$i] = $row[0];
            $i++;
        }
        return $column_names_array;
        mysqli_close( $conn );
    }
    function Insert($table_name, $fields_array, $values_array){
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
		if($result_insert = mysqli_query($conn, $query_insert)){
           return true;
		} else {
           return false;
        }
        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //$fields_array=array("testname","lastname","21321321","3541354");
        //$test_values=array("testname","lastname","21321321","3541354");
        //Insert('drivers',$fields_array,$test_values);
        mysqli_close( $conn );
    }
    function Delete($table_name,$condition){
        include('config.php');
        $query_delete = "DELETE FROM ".$table_name." ".$condition."";
        if($result_delete = mysqli_query($conn, $query_delete)){
            return true;
         } else {
            return false;
         }
        /***************************************************************************
                        //TEST
        ****************************************************************************/
         //Delete("drivers","WHERE first_name = 's' AND phone = 34");
         mysqli_close( $conn );
    }
    function Update($table_name,$fields_array,$values_array,$condition){
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
		if($result_update = mysqli_query($conn, $query_update)){
            return true;
		} else {
            return false;
        }
        /***************************************************************************
                        //TEST
        ****************************************************************************/
        //
        //$test_fields=array("last_name");
        //$test_values=array("newwwww");
        //Update("drivers",$test_fields,$test_values,"WHERE phone ='1'");
        mysqli_close( $conn );
    }
?>