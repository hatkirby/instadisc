/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Item;

import com.fourisland.instadisc.Database.Wrapper;
import java.util.Random;

/**
 *
 * @author hatkirby
 */
public class Verification {

    private String username;
    private String hash;
    private int id;

    public Verification() {
        Random r = new Random();
        id = r.nextInt(Integer.MAX_VALUE);
        username = Wrapper.getConfig("username");
        String temp = username + ":" + Wrapper.getConfig("password") + ":" + id;
        MD5 md5 = new MD5(temp);
        hash = md5.hash();
    }

    public Verification(int ID) throws VerificationIDReusedException {
        id = ID;
        if (Wrapper.containsOldVerID(id)) {
            throw new VerificationIDReusedException();
        } else {
            if (Wrapper.countOldVerID() == Integer.decode(Wrapper.getConfig("verIDBufferSize"))) {
                Wrapper.emptyOldVerID();
            }
            Wrapper.addOldVerID(id);
        }

        username = Wrapper.getConfig("username");
        String temp = username + ":" + Wrapper.getConfig("password") + ":" + id;
        MD5 md5 = new MD5(temp);
        hash = md5.hash();
    }

    public Verification(String username, String password) {
        Random r = new Random();
        id = r.nextInt(Integer.MAX_VALUE);
        String temp = username + ":" + password + ":" + id;
        MD5 md5 = new MD5(temp);
        hash = md5.hash();
        this.username = username;
    }
    
    public String getUsername()
    {
        return username;
    }

    public String getHash() {
        return hash;
    }

    public int getID() {
        return id;
    }
}
