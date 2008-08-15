/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc;

import com.fourisland.instadisc.Item.Item;
import java.io.IOException;
import java.io.InputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketException;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class InstaDiscThread implements Runnable {

    boolean cancelled = false;
    InstaDiscView idv;
    
    public InstaDiscThread(InstaDiscView idv)
    {
        this.idv = idv;
    }

    public void cancel() {
        cancelled = true;
    }

    public void run() {
        try
        {
            ServerSocket svr = new ServerSocket();
            java.net.InetSocketAddress addr = new java.net.InetSocketAddress(1204);
            svr.bind(addr);
            Runtime.getRuntime().addShutdownHook(new Thread(new CloseServerSocketThread(svr)));
            while (!cancelled)
            {
                try
                {
                    Socket s = svr.accept();
                    HandleItemThread hit = new HandleItemThread(s,idv);
                    Thread hitt = new Thread(hit);
                    hitt.start();
                } catch (SocketException ex)
                {
                    cancel();
                } catch (Exception ex)
                {
                    cancel();
                    Logger.getLogger(InstaDiscThread.class.getName()).log(Level.SEVERE, null, ex);
                }
            }
            svr.close();
        } catch (IOException ex)
        {
            Logger.getLogger(InstaDiscThread.class.getName()).log(Level.SEVERE, null, ex);
        }

    }
}

class HandleItemThread implements Runnable {

    Socket s;
    InstaDiscView idv;

    public HandleItemThread(Socket s, InstaDiscView idv) {
        this.s = s;
        this.idv = idv;
    }

    public void run() {
        idv.startProgress();
        idv.doText("Downloading Item....");
        
        try
        {
            InputStream is = s.getInputStream();
            int buffer[] = new int[1000];
            int rs = 0;
            int i = 0;

            while (rs != -1)
            {
                try
                {
                    rs = is.read();

                    if (rs != -1)
                    {
                        buffer[i] = rs;
                    }
                    
                    idv.doProgress(buffer.length / (is.available()+1));
                    
                    i++;
                } catch (SocketException ex)
                {
                    return;
                } catch (IOException ex)
                {
                    Logger.getLogger(HandleItemThread.class.getName()).log(Level.SEVERE, null, ex);
                }
            }

            StringBuilder result = new StringBuilder();
            int j = 0;
            for (j = 0; j < i; j++)
            {
                result.append(Character.toString((char) buffer[j]));
            }

            String[] headers = result.toString().split("\n");
            HashMap<String, String> headerMap = new HashMap<String, String>();
            i = 0;
            while (1 == 1)
            {
                try
                {
                    String[] nameVal = headers[i].split(": ");
                    String name = nameVal[0];
                    String value = nameVal[1].trim().replace("__INSTADISC__", ": ");
                    headerMap.put(name, value);
                } catch (Exception ex)
                {
                    break;
                }
                i++;
            }

            //Logger.getLogger(HandleItemThread.class.getName()).log(Level.INFO, headerMap.toString());
            try
            {
                s.close();
            } catch (IOException ex)
            {
                Logger.getLogger(HandleItemThread.class.getName()).log(Level.SEVERE, null, ex);
            }

            Item idI = new Item(headerMap);
            idI.start();
        } catch (IOException ex)
        {
            Logger.getLogger(HandleItemThread.class.getName()).log(Level.SEVERE, null, ex);
        }
        
        idv.doneProgress();
    }
}
