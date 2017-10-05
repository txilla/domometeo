<?php

    # We include other important files
    include ("../../Clases/user.php");
    include ("../../Clases/sqlite.php");
    include ("../../Clases/sensorValue.php");
    include ("../../Recursos/variables.php");

?>

<?php

  $sqliteConnection = new Cl_SQLite($ficheroSqlite);
  $rows = '';
  // Generamos por PHP 5 filas con la temperatura exterior, mostrando la fecha y el valor
  $sensorValuesArray = array();

  //echo $_POST['fecha'];
  //echo 'hola';
    $data="";
    if (!empty($_GET['fecha']))
    {
      $data=$_GET['fecha'];
    }
    else
    {
      $data= 'maloooo';
    }

    
    if (!empty($_GET['nodeId']))
    {
      $node=(int)$_GET['nodeId'];
    }
    else
    {
      $node= 'maloooo';
    }

    
    if (!empty($_GET['childId']))
    {
      $child=(int)$_GET['childId'];
    }
    else
    {
      $child= 'maloooo';
    }

    //echo "hola";
    $json_data=array();
    
    /*$sensorValuesArray = $sqliteConnection->getMax(1, 3, "_date", "desc", 6);
      foreach($sensorValuesArray as $i => $value) 
      {
        $timestamp = $value->date;


        $json_array['fecha']=$timestamp; 
        $json_array['dato']=$value->value; 
        array_push($json_data,$json_array);
      }

    $sensorValuesArray = $sqliteConnection->getMax(1, 3, "_date", "desc", 5);
      foreach($sensorValuesArray as $i => $value) 
      {
        $timestamp = $value->date;


        $json_array['fecha']=$timestamp; 
        $json_array['dato']=$value->value; 
        array_push($json_data,$json_array);
      } */


    //echo $data;
    //echo $node;
    //echo $child;

    $todayh = getdate();

    $d = $todayh['mday'];
    $m = $todayh['mon'];
    $y = $todayh['year'];

    $dia=$d;

    if ($m < 10)
    {
      if ( $d < 10)
      {
        $fecha = "$y-0$m-0$dia";
        $fecha2 = "0$dia-0$m-$y";
      }
      else
      {
        $fecha = "$y-0$m-$dia";
        $fecha2 = "$dia-0$m-$y";
      }
      
    }
    else
    {
      $fecha = "$y-$m-$dia";
      $fecha2 = "$dia-$m-$y";
    }
    

    //echo $fecha;

    for ($i = 1; $i <= 7; $i++) 
    {

      $sensorValuesArray = $sqliteConnection->getMax($node, $child, "_date", "desc", $fecha);
      foreach($sensorValuesArray as $j => $value) 
      {
        //$dia=$dia-1;
        //$fecha = "$y-$m-$dia";
        $timestamp = $value->date;


        $json_array['fecha']=$fecha2; 
        $json_array['dato']=$value->value; 
        
      }

      $sensorValuesArray = $sqliteConnection->getMin($node, $child, "_date", "desc", $fecha);
      foreach($sensorValuesArray as $j => $value) 
      {
        //$dia=$dia-1;
        $fecha = "$y-$m-$dia";
        $timestamp = $value->date;


        //$json_array['fecha']=$fecha; 
        $json_array['dato2']=$value->value; 
        
      }
      $dia=$dia-1;
      if ($m < 10)
      {
        if ( $dia < 10)
        {
          $fecha = "$y-0$m-0$dia";
          $fecha2 = "0$dia-0$m-$y";
        }
        else
        {
          $fecha = "$y-0$m-$dia";
          $fecha2 = "$dia-0$m-$y";
        }
        
      }
      else
      {
        $fecha = "$y-$m-$dia";
        $fecha2 = "$dia-$m-$y";
      }

      array_push($json_data,$json_array);
    
    }

     // Retornara una array con 10 objetos de sensorValues

   

    /*$json_data=array();
    foreach($sensorValuesArray as $i => $value) 
    {
      $elementos_json[] = "\"$codigo\": \"$nombre\"";
      

      //$timestamp = (\DateTime::createFromFormat('Y-m-d H:i:s', $value->date)->getTimestamp())*1000; // milliseconds from ephoc

      $timestamp = $value->date;


      $json_array['fecha']=$timestamp; 
      $json_array['dato']=$value->value; 
      array_push($json_data,$json_array); 
  }*/

  echo json_encode($json_data);
  
 ?>