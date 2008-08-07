package com.fourisland.instadisc.update.svn;

import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.Random;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.apache.xmlrpc.XmlRpcException;
import org.apache.xmlrpc.client.XmlRpcClient;
import org.apache.xmlrpc.client.XmlRpcClientConfigImpl;

public class Main {

    public static void main(String[] args) {
        try
        {
            String username = getArg(0, args);
            String password = getArg(1, args);
            String centralServer = getArg(2, args);
            String pathScheme = getArg(3, args);
            String author = getArg(4, args);
            String subscription = getArg(5, args);
            String revision = getArg(6, args);

            StringBuilder messBuilder = new StringBuilder();
            byte rs = 0;

            while (rs != -1)
            {
                try
                {
                    rs = (byte) System.in.read();
                    if (rs != -1)
                    {
                        messBuilder.append(new String(new byte[]{rs}));
                    }
                } catch (IOException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                }
            }

            String message = messBuilder.toString();
            message = message.substring(0, message.indexOf("\n"));

            Random r = new Random();
            int verID = r.nextInt(Integer.MAX_VALUE);

            String path = pathScheme.replace("__REV__", revision);

            XmlRpcClientConfigImpl config = new XmlRpcClientConfigImpl();
            config.setServerURL(new URL(centralServer));
            XmlRpcClient client = new XmlRpcClient();
            client.setConfig(config);
            client.execute("InstaDisc.sendFromUpdate", new Object[]{username,
                (new MD5(username + ":" + (new MD5(password)).hash() + ":" + verID)).hash(),
                verID,
                subscription,
                message,
                author,
                path,
                "a:0:{}"
            });
        } catch (XmlRpcException ex)
        {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        } catch (MalformedURLException ex)
        {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public static String getArg(int arg, String[] args) {
        if (args.length < (arg+1))
        {
            System.out.println("Program requires 7 arguments and you only provided " + arg);
            System.exit(1);
        }

        return args[arg];
    }
}
