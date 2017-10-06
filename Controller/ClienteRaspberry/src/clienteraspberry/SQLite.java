/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clienteraspberry;

import java.sql.*;
/**
 *
 * @author tXillA
 */

public class SQLite {

    
    Connection c = null;
    DatosServer datos = null;
    
    
    
    public boolean connect(String database)
    {
        Boolean conexioCorrecte = false;
        
        try 
        {
            //c = DriverManager.getConnection("jdbc:sqlite:..\\..\\..\\Base de datos\\SQLite\\domo.db");
            c = DriverManager.getConnection("jdbc:sqlite:"+database);
            if (c!=null) 
            {
                System.out.println("Conectado");
                conexioCorrecte =true;
            }
        }
        catch (Exception ex) 
        {
            System.err.println("No se ha podido conectar a la base de datos\n"+ex.getMessage());
        }
        return conexioCorrecte;
    }
    public void close()
    {
        try 
        {
            c.close();
            System.out.println("Conexion cerrada");
        } 
        catch (Exception ex) {
            System.err.println("No se ha podido CERRAR la base de datos\n"+ex.getMessage());
        }
    }
    
    public void insert(DatosServer datosServer)
    {
        Statement stmt = null;
        this.datos = datosServer;
        try 
        {
            stmt = c.createStatement();
            String sql = "INSERT INTO payload " +
                   "VALUES ("+ datos.getDate()+ ", "+ datos.getNode()+ ", "+ datos.getChildNode()+ ", "+ datos.getSensor()+ ", "+ datos.getPayload()+ " );"; 
            stmt.executeUpdate(sql);
            System.out.println("Insert correcto");
        } 
        catch (Exception ex) {
            System.err.println("No se ha podido insertar los datos la base de datos\n"+ex.getMessage());
        }
    }
    
    
    
}
