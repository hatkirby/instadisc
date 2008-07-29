/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Database;

import com.sleepycat.je.DatabaseException;
import com.sleepycat.je.Environment;
import com.sleepycat.je.EnvironmentConfig;
import com.sleepycat.persist.EntityCursor;
import com.sleepycat.persist.EntityStore;
import com.sleepycat.persist.PrimaryIndex;
import com.sleepycat.persist.StoreConfig;
import java.io.File;
import java.util.Iterator;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class Wrapper {

    public static Environment e = null;
    public static EntityStore es = null;
    public static PrimaryIndex<Integer, OldVerID> oldVerID;
    public static PrimaryIndex<String, IDConfig> idConfig;

    public static void init(String loc) {

        EnvironmentConfig envConfig = new EnvironmentConfig();
        StoreConfig esConfig = new StoreConfig();
        envConfig.setAllowCreate(true);
        esConfig.setAllowCreate(true);
        try {
            e = new Environment(new File(loc + "db"), envConfig);
            es = new EntityStore(e, "EntityStore", esConfig);
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            System.exit(1);
        }
        Runtime.getRuntime().addShutdownHook(new Thread(new CloseEnvironmentThread(e)));
        Runtime.getRuntime().addShutdownHook(new Thread(new CloseEntityStoreThread(es)));

        try {
            oldVerID = es.getPrimaryIndex(Integer.class, OldVerID.class);
            idConfig = es.getPrimaryIndex(String.class, IDConfig.class);
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    public static String getConfig(String key)
    {
        try {
            return idConfig.get(key).getValue();
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            return "";
        }
    }
    
    public static void setConfig(String key, String value)
    {
        try {
            if (idConfig.contains(key)) {
                IDConfig temp = idConfig.get(key);
                temp.setValue(value);
                idConfig.put(temp);
            } else {
                IDConfig temp = new IDConfig();
                temp.setKey(key);
                temp.setValue(value);
                idConfig.put(temp);
            }
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    public static boolean containsOldVerID(Integer id)
    {
        try {
            return oldVerID.contains(id);
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }
    }
    
    public static int countOldVerID()
    {
        try {
            return (int) oldVerID.count();
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            return 0;
        }
    }
    
    public static void emptyOldVerID()
    {
        try {
            EntityCursor<OldVerID> ec = oldVerID.entities();
            try {
                Iterator<OldVerID> i = ec.iterator();
                while (i.hasNext()) {
                    i.remove();
                }
            } finally {
                ec.close();
            }
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
    
    public static void addOldVerID(Integer id)
    {
        try {
            OldVerID temp = new OldVerID();
            temp.setID(id);
            oldVerID.put(temp);
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}
