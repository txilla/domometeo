<?php
    
    # Includes
    include ("../../Clases/process.php");

    /** FUNCTIONS AND METHODS **/

    function obtenerRam($tipo)
    {
        $usedMemory = 0.00;
        $command = 'free -m';
        $output = array();

        // Ejecutamos el comando que nos obtendra los datos que queremos y nos retornara una Array con los mismos separados
        $output = execCommand($command);

        // De las cadenas obtenidas, obtenemos la posición 1 que tiene siempre los datos y 
        // hacemos un split quitando los espacios y los tabuladores para obtener solo los datos
        $datos = preg_split('@\s+@', trim($output[1]), 7 );

        // De la array de datos recogemos la memoria que quieren obtener
        switch ($tipo)
        {
            case 'disponible':
                $usedMemory = $datos[3];
            break;
            case 'usada':
                $usedMemory = $datos[2];
            break;
            case 'total':
                $usedMemory = $datos[1];
            break;
        }

        return ($usedMemory);
    }

    function existeProceso($nameArgs)
    {
        $existe = false;
        $output = array();

        // Este comando si existe el proceso tiene que retornar 3, el nombre del proceso encendido y 
        // 2 mas que son los actuales al ejecutar este comando pk este comando contiene un parametro con el mismo nombre
        $command = "ps -ef | grep ".$nameArgs."";    

        $output = execCommand($command);

        if(count($output) == 3)
        {
            $existe = true;
        }

        return ($existe);
    }

    function obtenerCargaCpu()
    {
        $cargaCPU = 0;
        $resultArray = array();
        
        // Comando en bash para obtener la carga de CPU en tanto por cierto a traves del comando top
        $command = 'ps -A -o pcpu | tail -n+2 | paste -sd+ | bc';
        $resultArray = execCommand($command);

        $cargaCPU = $resultArray[0];

        // Si la carga de la CPU supera el 100% le asignamos 100 ya que es el máximo tanto por ciento que mostramos
        if($cargaCPU > 100)
            $cargaCPU = 100;
        else if ($cargaCPU < 1)
            $cargaCPU = 1;

        // Return the total charge
        return ($cargaCPU);
    }

    function getAllProcess()
    {
        $process = new Cl_Process();
        $output = array();
        $processList = array();
        $command = 'ps axo pid,%cpu,pmem,user,args --sort %cpu';

        // Call to the execCommand function to get all the process from the system in an array process for process
        $output = execCommand($command);

        // Transform the string array to an array of Cl_Process
        $processList = $process->strArray_to_ProcessArray($output);

        return $processList;
    }

    function execCommand($cmd)
    {
        $output = array();
        $val = -1;
        $cadena = "";

        // Execute the command and put all the information into an Array
        $cadena = exec($cmd, $output, $val);

        // Return the Array created
        return $output;
    }


?>