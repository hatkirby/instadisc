package com.fourisland.instadisc.DownloadItem;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.Item.Item;
import com.fourisland.instadisc.XmlRpc;
import java.io.IOException;
import java.io.InputStream;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketException;
import java.util.logging.Level;
import java.util.logging.Logger;


public class PushMode implements DownloadItemMode
{
    InstaDiscThread idt;
    
    public void modeInitalize()
    {
        XmlRpc xmlrpc = new XmlRpc("initalizePort");
        int port = (Integer) xmlrpc.execute();
        
        idt = new InstaDiscThread(port);
        new Thread(idt).start();
    }
    
    public void modeDeinitalize()
    {
        idt.kill();
        
        XmlRpc xmlrpc = new XmlRpc("deinitalizePort");
        xmlrpc.execute();
    }

    public void requestRetained() {
        XmlRpc xmlrpc = new XmlRpc("requestRetained");
        xmlrpc.execute();
    }
    
    public void resendItem(int id) {
        XmlRpc xmlrpc = new XmlRpc("resendItem");
        xmlrpc.addParam(id);
        xmlrpc.execute();
    }

    public int setTimer() {
        int delay = (1000 * 60 * 60);
        if (Wrapper.getConfig("ipCheckUnit").equals("day")) {
            delay *= (24 * Integer.decode(Wrapper.getConfig("ipCheckValue")));
        } else if (Wrapper.getConfig("ipCheckUnit").equals("hour")) {
            delay *= Integer.decode(Wrapper.getConfig("ipCheckValue"));
        }
        
        return delay;
    }

    public void timerTick() {
        XmlRpc xmlrpc = new XmlRpc("checkRegistration");
        xmlrpc.execute();
    }
}
class InstaDiscThread implements Runnable {

    boolean cancelled = false;
    ServerSocket svr;
    int port;
    
    public InstaDiscThread(int port)
    {
        this.port = port;
    }

    public void cancel() {
        cancelled = true;
    }

    public void run() {
        try
        {
            svr = new ServerSocket();
            java.net.InetSocketAddress addr = new java.net.InetSocketAddress(port);
            svr.bind(addr);
            while (!cancelled)
            {
                try
                {
                    Socket s = svr.accept();
                    HandleItemThread hit = new HandleItemThread(s);
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
    
    public void kill()
    {
        cancel();
        
        try
        {
            svr.close();
        } catch (IOException ex)
        {
            Logger.getLogger(InstaDiscThread.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}

class HandleItemThread implements Runnable {

    Socket s;

    public HandleItemThread(Socket s) {
        this.s = s;
    }

    public void run() {
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

            try
            {
                s.close();
            } catch (IOException ex)
            {
                Logger.getLogger(HandleItemThread.class.getName()).log(Level.SEVERE, null, ex);
            }

            Item idI = new Item(result.toString());
            idI.start();
        } catch (IOException ex)
        {
            Logger.getLogger(HandleItemThread.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}
