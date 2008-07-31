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
    public static PrimaryIndex<Integer, Item> item;
    public static PrimaryIndex<String, Subscription> subscription;

    public static void init(String loc) {

        EnvironmentConfig envConfig = new EnvironmentConfig();
        StoreConfig esConfig = new StoreConfig();
        envConfig.setAllowCreate(true);
        esConfig.setAllowCreate(true);
        try {
            e = new Environment(new File(loc), envConfig);
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
            item = es.getPrimaryIndex(Integer.class, Item.class);
            subscription = es.getPrimaryIndex(String.class, Subscription.class);
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public static String getConfig(String key) {
        synchronized (idConfig) {
            try {
                return idConfig.get(key).getValue();
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return "";
            }
        }
    }

    public static void setConfig(String key, String value) {
        synchronized (idConfig) {
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
    }

    public static boolean containsOldVerID(Integer id) {
        try {
            return oldVerID.contains(id);
        } catch (DatabaseException ex) {
            Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            return false;
        }
    }

    public static int countOldVerID() {
        synchronized (oldVerID) {
            try {
                return (int) oldVerID.count();
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return 0;
            }
        }
    }

    public static void emptyOldVerID() {
        synchronized (oldVerID) {
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
    }

    public static void addOldVerID(Integer id) {
        synchronized (oldVerID) {
            try {
                OldVerID temp = new OldVerID();
                temp.setID(id);
                oldVerID.put(temp);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }

    public static void addItem(Item m_item) {
        synchronized (item) {
            try {
                item.put(m_item);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }

    public static int countItem() {
        synchronized (item) {
            try {
                return (int) item.count();
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return 0;
            }
        }
    }

    public static void dropFromTopItem() {
        synchronized (item) {
            try {
                Integer[] keySet = (Integer[]) item.map().keySet().toArray();
                item.delete(keySet[0]);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }

    public static Item[] getAllItem() {
        synchronized (item) {
            try {
                Iterator<Item> i = item.entities().iterator();
                Item[] temp = new Item[0];
                int len = 0;

                while (i.hasNext()) {
                    Item[] temp2 = new Item[len + 1];
                    int j = 0;
                    for (j = 0; j < len; j++) {
                        temp2[j] = temp[j];
                    }
                    temp2[len] = i.next();
                    temp = temp2;
                }

                return temp;
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return new Item[0];
            }
        }
    }

    public static Subscription getSubscription(String url) {
        synchronized (subscription) {
            try {
                return subscription.get(url);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return null;
            }
        }
    }

    public static boolean existsSubscription(String url) {
        synchronized (subscription) {
            try {
                return subscription.contains(url);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return false;
            }
        }
    }

    public static void addSubscription(Subscription s) {
        synchronized (subscription) {
            try {
                subscription.put(s);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
    
    public static Subscription[] getAllSubscription() {
        synchronized (subscription) {
            try {
                Iterator<Subscription> i = subscription.entities().iterator();
                Subscription[] temp = new Subscription[0];
                int len = 0;

                while (i.hasNext()) {
                    Subscription[] temp2 = new Subscription[len + 1];
                    int j = 0;
                    for (j = 0; j < len; j++) {
                        temp2[j] = temp[j];
                    }
                    temp2[len] = i.next();
                    temp = temp2;
                }

                return temp;
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
                return new Subscription[0];
            }
        }
    }
    
    public static void deleteSubscription(String url)
    {
        synchronized (subscription)
        {
            try {
                subscription.delete(url);
            } catch (DatabaseException ex) {
                Logger.getLogger(Wrapper.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
}
