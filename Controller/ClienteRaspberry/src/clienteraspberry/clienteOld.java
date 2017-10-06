/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package clienteraspberry;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author tXillA
 */
public class clienteOld {

    
    /**
     * @param args the command line arguments
     */
    /*public static void main(String[] args) {
        
        String db = "..\\..\\..\\Base de datos\\SQLite\\domo.db";
        String ipServidor ="192.168.1.91";
        if (args.length > 0)
        {
            db = args[0];
            ipServidor = args[1];
        }
        
        SQLite con = new SQLite();
        con.connect(db);
        
        //con.insert();
        //con.close();
        // TODO code application logic here
        Socket cliente = null;
	DataInputStream entrada = null;
	DataOutputStream salida = null;
        
        boolean dentro=true;
        
		  
	//nos conectamos al localhost a traves de esta dirección IP
 
	//if (cliente != null && salida != null && entrada!= null) {	
        try {	
                System.out.println("Conectando con el servidor... Espere...");
                cliente = new Socket(ipServidor, 5003);
                System.out.println("Conectado");
                //asignamos este numero de puerto
                entrada = new DataInputStream(cliente.getInputStream());
                // será lo que enviaremos al servidor	
                salida = new DataOutputStream(cliente.getOutputStream());
                // será lo que nos devuelva el servidor	

        }
        catch (UnknownHostException excepcion) {
                System.err.println("El servidor no está levantado");
        }
        catch (Exception e) {
                System.err.println("Error: " + e );
        }
        
        //SQLite con = new SQLite();
        //con.connect();
        
        while (dentro)
        {
            try 
            {
                String linea_recibida;
                linea_recibida = entrada.readLine();
                System.out.println("SERVIDOR DICE: " + linea_recibida);
                
                String[] array = linea_recibida.split(";");
                
                if ( array[2].equals("1") && !array[5].equals("") )
                {
                    String sql = "INSERT INTO payload " +
                   "VALUES (DateTime('now'),"+ Integer.parseInt(array[0])+ ", "+ Integer.parseInt(array[1])+ ", "+ Double.parseDouble(array[5])+ " );";
                    
                    con.insert(sql);
                    
                    
                }
                
            } 
            catch (UnknownHostException excepcion) {
                    System.err.println("No encuentro el servidor en la dirección" + ipServidor);
            }
            catch (Exception e) {
                    System.err.println("Error: " + e );

            }
        }
        
        try {
            salida.close();
            entrada.close();
            cliente.close();
        } catch (IOException ex) {
            System.err.println("Error de entrada/salida");
        }
                

    }*/
    
}
