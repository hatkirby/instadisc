package com.fourisland.instadisc.update.svn;

import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.security.InvalidAlgorithmParameterException;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.util.Random;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.IllegalBlockSizeException;
import javax.crypto.NoSuchPaddingException;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;
import org.apache.xmlrpc.XmlRpcException;
import org.apache.xmlrpc.client.XmlRpcClient;
import org.apache.xmlrpc.client.XmlRpcClientConfigImpl;

public class Main {

    public static void main(String[] args) {
        try
        {
            String pathScheme = getArg(1, args);
            String author = getArg(2, args);
            String seriesURL = getArg(3, args);
            String subscriptionID = getArg(4, args);
            String revision = getArg(5, args);

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

            String path = pathScheme.replace("__REV__", revision);

            Random r = new Random();
            int verID = r.nextInt(Integer.MAX_VALUE);
            int encID = 0;

            if (args.length > 7)
            {
                encID = r.nextInt(Integer.MAX_VALUE);
                MD5 md5 = new MD5(padright(args[7], new Integer(encID).toString(), 16).substring(0, 16));
                String key = md5.hash().substring(0, 16);
                String iv = reverse(key);

                try
                {
                    Cipher cipher = Cipher.getInstance("AES/CBC/NoPadding");
                    SecretKeySpec keySpec = new SecretKeySpec(key.getBytes(), "AES");
                    IvParameterSpec ivSpec = new IvParameterSpec(iv.getBytes());
                    cipher.init(Cipher.ENCRYPT_MODE, keySpec, ivSpec);

                    message = new String(bytesToHex(cipher.doFinal(pad(message.getBytes())))).trim();
                    author = new String(bytesToHex(cipher.doFinal(pad(author.getBytes())))).trim();
                    path = new String(bytesToHex(cipher.doFinal(pad(path.getBytes())))).trim();
                } catch (IllegalBlockSizeException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                } catch (BadPaddingException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                } catch (InvalidKeyException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                } catch (InvalidAlgorithmParameterException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                } catch (NoSuchAlgorithmException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                } catch (NoSuchPaddingException ex)
                {
                    Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
                }
            }

            XmlRpcClientConfigImpl config = new XmlRpcClientConfigImpl();
            config.setServerURL(new URL(centralServer));
            XmlRpcClient client = new XmlRpcClient();
            client.setConfig(config);
            Integer resp = (Integer) client.execute("InstaDisc.sendFromUpdate", new Object[]{seriesURL,
                subscriptionID,
                message,
                author,
                path,
                "a:0:{}",
                encID
            });

            if (resp == 2)
            {
                main(args);
            }
        } catch (XmlRpcException ex)
        {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        } catch (MalformedURLException ex)
        {
            Logger.getLogger(Main.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public static String getArg(int arg, String[] args) {
        if (args.length < (arg + 1))
        {
            System.out.println("Program requires 7 arguments and you only provided " + arg);
            System.exit(1);
        }

        return args[arg];
    }

    public static String reverse(String in) {
        String out = "";
        int i = 0;

        for (i = 0; i < in.length(); i++)
        {
            out = in.charAt(i) + out;
        }

        return out;
    }

    public static String padright(String in, String pad, int len) {
        while (in.length() < len)
        {
            in += pad;
        }

        if (in.length() > len)
        {
            in = in.substring(0, len);
        }

        return in;
    }

    public static String bytesToHex(byte[] buffer) {
        if (buffer == null)
        {
            return null;
        } else
        {
            StringBuilder result = new StringBuilder();
            for (int i = 0; i < buffer.length; i++)
            {
                String hex = Integer.toHexString(Integer.decode(new Byte(buffer[i]).toString()));
                result.append(padleft(hex.substring(max(hex.length() - 2, 0)), "0", 2));
            }

            return result.toString();
        }
    }

    public static int max(int x, int y) {
        return (x > y ? x : y);
    }

    public static String padleft(String in, String pad, int len) {
        while (in.length() < len)
        {
            in = pad + in;
        }

        if (in.length() > len)
        {
            in = in.substring(0, len);
        }

        return in;
    }
    
    public static byte[] pad(byte[] buffer)
    {
        while (buffer.length % 16 != 0)
        {
            byte[] tmp = new byte[buffer.length+1];
            int i=0;
            for (i=0;i<buffer.length;i++)
            {
                tmp[i] = buffer[i];
            }
            tmp[buffer.length] = 0;
            buffer = tmp;
        }
        return buffer;
    }
}
