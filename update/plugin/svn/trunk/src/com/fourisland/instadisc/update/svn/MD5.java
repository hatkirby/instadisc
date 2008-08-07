/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.update.svn;

import java.security.MessageDigest;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class MD5 {
    
    String ver;

    public MD5(String ver) {
        this.ver = ver;
    }
    
    public MD5(char[] password) {
        int i=0;
        ver="";
        for (i=0;i<password.length;i++)
        {
            ver += password[i];
            password[i] = 0;
        }
    }
    
    public String hash()
    {
        StringBuilder verify = new StringBuilder();
        try {
            MessageDigest md5 = MessageDigest.getInstance("MD5");
            int i = 0;
            byte[] create = new byte[ver.length()];
            for (i = 0; i < ver.length(); i++) {
                create[i] = (byte) ver.charAt(i);
            }
            byte buffer[] = md5.digest(create);
            for (i = 0; i < buffer.length; i++) {
                String hex = Integer.toHexString(buffer[i]);
                verify.append(pad(hex.substring(max(hex.length() - 2, 0)),"0",2));
            }
        } catch (Exception ex) {
            Logger.getLogger(MD5.class.getName()).log(Level.SEVERE, null, ex);
        }
        ver = "";
        return verify.toString();
    }
    
    private int max(int x, int y)
    {
        return (x > y ? x : y);
    }
    
    private String pad(String in, String pad, int len)
    {
        while (in.length() < len)
        {
            in = pad + in;
        }
        return in;
    }
}
