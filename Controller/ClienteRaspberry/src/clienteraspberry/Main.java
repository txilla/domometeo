/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clienteraspberry;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author tXillA
 */
public class Main {

    
    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        
        
        SQLite con = null;
        ClienteRpi cliente = null;
        String db = "..\\..\\..\\Base de datos\\SQLite\\domo.db";
        String ipServidor ="192.168.1.91";
        if (args.length > 0)
        {
            if (args.length > 2 || args.length ==1)
            {
                System.out.println("Parametros incorrectos.");
                System.out.println("Uso: ");
                System.out.println("  java -jar <ClienteRaspberry.jar>" );
                System.out.println("  java -jar <ClienteRaspberry.jar> <bbdd> <ip servidor>");
                
                return;
            }
            else
            {
                db = args[0];
                ipServidor = args[1];
            }
        }
        
        
        con = new SQLite();
        
        File fichero = new File(db);

        if (fichero.exists())
        {
            if(con.connect(db))
            {
                cliente = new ClienteRpi(con);
                if(cliente.conectar(ipServidor))
                {
                    cliente.tratarDatos();
                }
                else
                {
                    System.out.println("No se puede conectar al servidor");
                }
            }
            else
            {
                System.out.println("No se puede conectar a la BBDD");
            }
        }
        else
        {
            System.out.println("BBDD no encontrada");
        }
        
        //con.insert();
        //con.close();
        // TODO code application logic here
        
                

    }
    
}

