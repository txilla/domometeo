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

    //echo $data;
    //echo $node;
    //echo $child;

    $sensorValuesArray = $sqliteConnection->getSensorDateValue($node, $child, "_date", "desc", $data); // Retornara una array con 10 objetos de sensorValues
    $l=0;
    
   

    $json_data=array();
    foreach($sensorValuesArray as $i => $value) 
    {
      $elementos_json[] = "\"$codigo\": \"$nombre\"";
      

      $timestamp = (\DateTime::createFromFormat($dateFormat, $value->date)->getTimestamp())*1000; // milliseconds from ephoc

      //$timestamp = $value->date;

      if ($data=="mes")
      {
        
        if ($l==0)
        {
          $json_array['fecha']=$timestamp; 
          $json_array['dato']=$value->value; 
          array_push($json_data,$json_array);
          $l++;
        }
        else if ($l>=11)
        {
          $l=0;
        }
        else
        {
          $l++;
        }

        
      }
      else
      {
        $json_array['fecha']=$timestamp; 
        $json_array['dato']=$value->value; 
        array_push($json_data,$json_array); 
      }
      
  }

  echo json_encode($json_data);
  
 ?>
