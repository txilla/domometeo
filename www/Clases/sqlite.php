<?php

    class Cl_SQLite
    {     
        private $filename = "";

        /**
        * Constructor de la clase que guarda la ruta del fichero de Base de datos
        * @param string $xfilename ruta del fichero de base de datos SQLite
        */
        public function __construct($xfilename)
        {
            $this->filename = $xfilename;
        }

        /**
        * Setter nos permite re-asignar la ruta del fichero de base de datos
        * @param string $xfilename ruta del fichero de base de datos SQLite
        */
        public function setFilename($xfilename) { $this->filename = $xfilename; }

        /****************** USER ACTIONS *************************/
        
        /**
        * Nos permite cambiar la contraseña de un usuario de la base de datos
        * @return boolean retorna true si se ha podido cambiar
        * @param string $xusername nombre del usuario
        * @param string $xpassword nueva contraseña
        */
        public function changePassword($xusername, $xpassword)
        {
            $passwordChanged = false;

            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("UPDATE users SET password = '".$xpassword."' WHERE _userName='".$xusername."'");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
            }
            else
            {
                $passwordChanged = true;
            }

            return ($passwordChanged);
        }

        /**
        * Esta función nos dice si el usuario pasado por parametro existe en la Base de datos
        * @return boolean
        * @param string $username nombre de usuario
        */
        public function userExist($username)
        {
            $exist = false;

            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("SELECT COUNT(*) FROM users WHERE _userName='".$username."'");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // If we obtain a result the username exist
                while($row = $result->fetchArray())
                {
                    if($row[0] >= 1)
                    {
                        $exist = true;
                    }
                }
            }

             // We close the connection with the database file and save memory
            $sqliteConnection->close();
            unset($sqliteConnection);

            return ($exist);            
        }

        /**
        * Esta función nos permite recuperar una Array de objetos Cl_User de todos los usuarios de la base de datos
        * @return Cl_User[] array de objetos Cl_User
        */
        public function getUsers()
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("SELECT * FROM users");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $userList = array(); 

                while($row = $result->fetchArray())
                {
                    // 
                    $user = new Cl_User($row['_userName'], "", $row['role'], $row['status']); // We don't add the password for security reasons, and also is not necessary
                    $userList[] = $user;
                }

            }

            // We close the connection with the database file and save memory
            $sqliteConnection->close($this->filename);
            unset($sqliteConnection);

            // Return the user list created before
            return ($userList);
        }

        /**
        * Esta función nos permite recuperar una Array de objetos Cl_User de los usuarios activos de la base de datos
        * @return Cl_User[] array de objetos Cl_User
        */
        public function getActiveUsers()
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("SELECT * FROM users WHERE status = 1");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $userList = array(); 

                while($row = $result->fetchArray())
                {
                    // 
                    $user = new Cl_User($row['_userName'], "", $row['role'], $row['status']); // We don't add the password for security reasons, and also is not necessary
                    $userList[] = $user;
                }

            }

            // We close the connection with the database file and save memory
            $sqliteConnection->close($this->filename);
            unset($sqliteConnection);

            // Return the user list created before
            return ($userList);

        }

        /**
        * Esta función nos permite recuperar una Array de objetos Cl_User de los usuarios inactivos de la base de datos
        * @return Cl_User[] array de objetos Cl_User
        */
        public function getInactiveUsers()
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("SELECT * FROM users WHERE status = 0");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $userList = array(); 

                while($row = $result->fetchArray())
                {
                    // 
                    $user = new Cl_User($row['_userName'], "", $row['role'], $row['status']); // We don't add the password for security reasons, and also is not necessary
                    $userList[] = $user;
                }

            }

            // We close the connection with the database file and save memory
            $sqliteConnection->close($this->filename);
            unset($sqliteConnection);

            // Return the user list created before
            return ($userList);

        }
        
        /**
        * Esta función nos permite recuperar un usuario de la base de datos a partir del nombre de usuario
        * @return ClUser objeto user
        * @param string $username nombre de usuario 
        */
        public function getUser($username)
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("SELECT * FROM users WHERE _userName='".$username."'");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the user object and complete the propierties
                $user = new Cl_User();     

                while($row = $result->fetchArray())
                {
                    $user->username = $row[0];
                    $user->password = $row[1];
                    $user->role = $row[2];
                    $user->enabled = $row[3];
                }

            }

            // We close the connection with the database file and save memory
            $sqliteConnection->close($this->filename);
            unset($sqliteConnection);

            // Return the user created before
            return ($user);

        }

        /**
        * Esta función guarda un objeto Cl_User en la base de datos
        * @param Cl_User $xuser objeto Cl_User
        */
        public function saveUser($xuser)
        {
            $userSaved = false;

            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the insert into the database
            $result = $sqliteConnection->query("INSERT INTO users (_userName, password, role, status) VALUES('".$xuser->username."', '".$xuser->password."', '".$xuser->role."', ".$xuser->enabled.")");
            //echo "INSERT INTO users (_userName, password, role, status) VALUES('".$xuser->username."', '".$xuser->password."', '".$xuser->role."', ".$xuser->enabled."";

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                $userSaved = true; // Insert correct
            }

            return ($userSaved); // Return statement
        }

        /**
        * Esta funcion nos elimina el usuario que le pasemos por parametro
        * @param string $username nombre del usuario a eliminar
        */
        public function deleteUser($username)
        {
            $userDeleted = false;

            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("DELETE FROM users WHERE _userName='".$username."'");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
            }
            else
            {
                $userDeleted = true;
            }

            return ($userDeleted);
        }

        /**
        * Esta funcion nos activa el usuario que le pasemos por parametro
        * @param string $username nombre del usuario a eliminar
        */
        public function activateUser($username)
        {
            $userActivated = false;

            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("UPDATE users SET status = 1 WHERE _userName='".$username."'");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
            }
            else
            {
                $userActivated = true;
            }

            return ($userActivated);
        }

        /**
        * Esta función nos deshabilita el usuario que le pasemos por parametro
        * @param string $username nombre del usuario a deshabilitar
        */
        public function disableUser($username)
        {
            $userDisabled = false;

            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query
            $result = $sqliteConnection->query("UPDATE users SET status = 0 WHERE _userName='".$username."'");

            // If the result is not correct return the error and exit the connection
            if(!$result)
            {
                $sqliteConnection->close($this->filename);
            }
            else
            {
                $userDisabled = true;
            }

            return ($userDisabled);
        }

        /***************** SENSOR VALUE ACTIONS *******************/

        /**
        * Esta funcion nos retorna un conjunto de objetos en una Array de tipo Cl_SensorValue
        *
        * @return Cl_SensorValue array con objetos si hay datos
        * @param int $nodeId id del nodo del que queremos los datos
        * @param int $sensorType tipo de sensor del que queremos los datos
        * @param string $columnOrder columna de la base de datos por la que queremos ordenar
        * @param string $orderType tipo de orden si es ascendiente o descendiente, por defecto es descendiente
        * @param int $numberOfValues numero de filas limite que nos mostrara la consulta contra la base de datos, por defecto es 5
        */
        public function getSensorValue($nodeId, $childNode, $columnOrder, $orderType = "desc", $numberOfValues = 5)
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query to the database
            $result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." order by ". $columnOrder ." ". $orderType . " LIMIT " . $numberOfValues);

            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $sensorArray = array();

                while($row = $result->fetchArray())
                {
                    // Create a sensorValue object with all the information
                    $sensorValue = new Cl_SensorValue($row["_date"], $row["_nodeId"],$row["_childNode"] ,$row["sensorType"], $row["value"]);
                    // Add it to the array of sensorValues
                    $sensorArray[] = $sensorValue;
                }

                // Return the Array of Cl_SensorValue objects
                return ($sensorArray);
            }
        }

        public function getSensorDateValue($nodeId, $childNode, $columnOrder, $orderType = "desc",$fecha)
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query to the database
            if ($fecha == "dia")
            {
                $interval = '-1 days';
                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date>datetime('2017-04-13 00:00:00') order by ". $columnOrder ." ". $orderType);

                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') order by ". $columnOrder ." ". $orderType);

                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','-1 days') order by ". $columnOrder ." ". $orderType);

                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." order by ". $columnOrder ." ". $orderType . " LIMIT 10");
            }
            else if ($fecha == "semana")
            {
                $interval = '-6 days';
                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','-6 days') order by ". $columnOrder ." ". $orderType);
                
            }
            else if ($fecha == "mes")
            {
                $interval = 'start of month';
                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','start of month') order by ". $columnOrder ." ". $orderType);
            }
            else
            {
                $interval = '-6 days';
                //$result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." order by ". $columnOrder ." ". $orderType . " LIMIT 10");
            }

            $result = $sqliteConnection->query("SELECT * FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','".$interval."') order by ". $columnOrder ." ". $orderType);
            

            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $sensorArray = array();

                while($row = $result->fetchArray())
                {
                    // Create a sensorValue object with all the information
                    $sensorValue = new Cl_SensorValue($row["_date"], $row["_nodeId"],$row["_childNode"] ,$row["sensorType"], $row["value"]);
                    // Add it to the array of sensorValues
                    $sensorArray[] = $sensorValue;
                }

                // Return the Array of Cl_SensorValue objects
                return ($sensorArray);
            }
        }

        public function getMax($nodeId, $childNode, $columnOrder, $orderType = "desc",$fecha)
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query to the database

            $fecha2 = $fecha - 1;


            /*if ($fecha2 == 0)
            {
                $result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','-".$fecha." days')");
            }
            else
            {
                $result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now','-".$fecha2." days') and _date>datetime('now','-".$fecha." days')");
            }*/

             //$result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<'2017-05-13 23:59:59' and _date>'2017-05-13 00:00:00'");

            $result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<'".$fecha." 23:59:59' and _date>'".$fecha." 00:00:00'");
            
            //$result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now','-1 days') and _date>datetime('now','-2 days')");
            

            //$result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','-".$fecha." days')");

            //$result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode);
            

            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $sensorArray = array();

                while($row = $result->fetchArray())
                {
                    // Create a sensorValue object with all the information
                    $sensorValue = new Cl_SensorValue($row["_date"], $row["_nodeId"],$row["_childNode"] ,$row["sensorType"], $row["max(value)"]);
                    // Add it to the array of sensorValues
                    $sensorArray[] = $sensorValue;

                    
                }

                // Return the Array of Cl_SensorValue objects
                return ($sensorArray);
            }


        }

        public function getMin($nodeId, $childNode, $columnOrder, $orderType = "desc",$fecha)
        {
            // Create SQLite object
            $sqliteConnection = new SQLite3($this->filename);

            // Execute the query to the database

            //$fecha2 = $fecha - 1;


            /*if ($fecha2 == 0)
            {
                $result = $sqliteConnection->query("SELECT min(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','-".$fecha." days')");
            }
            else
            {
                $result = $sqliteConnection->query("SELECT min(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now','-".$fecha2." days') and _date>datetime('now','-".$fecha." days')");
            }*/
            
            //$result = $sqliteConnection->query("SELECT min(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<'2017-05-13 23:59:59' and _date>'2017-05-13 00:00:00'");

            $result = $sqliteConnection->query("SELECT min(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<'".$fecha." 23:59:59' and _date>'".$fecha." 00:00:00'");
            

            //$result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode ." and _date<datetime('now') and _date>datetime('now','-".$fecha." days')");

            //$result = $sqliteConnection->query("SELECT max(value),_date,_nodeId,_childNode,sensorType FROM payload WHERE _nodeId = ".$nodeId." and _childNode=". $childNode);
            

            if(!$result)
            {
                $sqliteConnection->close($this->filename);
                exit($sqliteConnection->lastErrorMsg());
            }
            else
            {
                // Create the sensorValue object Array
                $sensorArray = array();

                while($row = $result->fetchArray())
                {
                    // Create a sensorValue object with all the information
                    $sensorValue = new Cl_SensorValue($row["_date"], $row["_nodeId"],$row["_childNode"] ,$row["sensorType"], $row["min(value)"]);
                    // Add it to the array of sensorValues
                    $sensorArray[] = $sensorValue;

                    
                }

                // Return the Array of Cl_SensorValue objects
                return ($sensorArray);
            }


        }



    } 

?>
