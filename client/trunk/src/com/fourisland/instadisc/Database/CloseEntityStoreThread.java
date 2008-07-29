/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc.Database;

import com.sleepycat.je.DatabaseException;
import com.sleepycat.persist.EntityStore;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class CloseEntityStoreThread implements Runnable {
    
    EntityStore es;

    public CloseEntityStoreThread(EntityStore es) {
        this.es = es;
    }

    public void run() {
        try {
            es.close();
        } catch (DatabaseException ex) {
            Logger.getLogger(CloseEntityStoreThread.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
