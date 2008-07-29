/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc;

import java.io.IOException;
import java.net.ServerSocket;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hatkirby
 */
class CloseServerSocketThread implements Runnable {
    
    ServerSocket svr;

    public CloseServerSocketThread(ServerSocket svr) {
        this.svr = svr;
    }

    public void run() {
        try {
            svr.close();
        } catch (IOException ex) {
            Logger.getLogger(CloseServerSocketThread.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

}
