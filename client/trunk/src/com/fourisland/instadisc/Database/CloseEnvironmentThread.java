/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc.Database;

import com.sleepycat.je.DatabaseException;
import com.sleepycat.je.Environment;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
public class CloseEnvironmentThread implements Runnable{

    Environment svr;

    public CloseEnvironmentThread(Environment e) {
        svr = e;
    }

    public void run() {
        try {
            svr.cleanLog();
            svr.close();
        } catch (DatabaseException ex) {
            Logger.getLogger(CloseEnvironmentThread.class.getName()).log(Level.SEVERE, null, ex);
        }
    }
}
