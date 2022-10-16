<?php
//setting header to json
header('Content-Type: application/json');
session_start();
if (isset($_GET['tableName'])){
  // Include config file
  require_once "../../../classes/DBController.php";
  $db_handle = new DBController();
  $username = $_SESSION["username"];
  $result_info = $db_handle->runBaseQuery("SELECT graph_division, graph_length, graph_type FROM settings WHERE username = '$username'");
  $division = $result_info[0]["graph_division"];
  $length = $result_info[0]["graph_length"];
  $type = $result_info[0]["graph_type"];
  $data = array();
  $data[] = array("chartType"=>$type, "chartLength"=>$length);
  $math_number = 1;
  if ($division == "hour"){
    $math_number = 12;
  } else if ($division == "day"){
    $math_number = 12*24;
  } else if ($division == "month"){
    $math_number = 12*24*30;
  } else if ($division == "year"){
    $math_number = 12*24*365.25;
  }
  $length = $length*$math_number;
  $tableName = $_GET['tableName'];
  //database
  // Include config file
  $result = $db_handle->custom_runBaseQuery("Agrismart", "SELECT Temperature, Humidity, WaterLevel, BatteryLevel, ChargingCurrent, Watering, Time FROM $tableName ORDER BY id DESC LIMIT $length");
  //loop through the returned data
  for ($i=0; $i<count($result); $i++) {
    if ($i % $math_number == 0){
      $data[] =$result[$i];
    }
  }
  
  //now print the data
  print json_encode($data);
}
?>