/*
 * InstaDiscApp.java
 */
package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.FirstRun.FirstRunWizard;
import java.io.File;
import org.jdesktop.application.Application;
import org.jdesktop.application.SingleFrameApplication;

/**
 * The main class of the application.
 */
public class InstaDiscApp extends SingleFrameApplication {
    
    public static String base;

    /**
     * At startup create and show the main frame of the application.
     */
    @Override
    protected void startup() {
        show(new InstaDiscView(this));
    }

    /**
     * This method is to initialize the specified window by injecting resources.
     * Windows shown in our application come fully initialized from the GUI
     * builder, so this additional configuration is not needed.
     */
    @Override
    protected void configureWindow(java.awt.Window root) {
    }

    /**
     * A convenient static getter for the application instance.
     * @return the instance of InstaDiscApp
     */
    public static InstaDiscApp getApplication() {
        return Application.getInstance(InstaDiscApp.class);
    }

    /**
     * Main method launching the application.
     */
    public static void main(String[] args) {
        if (args.length > 0) {
            base = args[0];
            
            File db = new File(args[0] + "db");
            if (!db.exists()) {
                db.mkdir();
            }
            
            Wrapper.init(args[0]);
            if (args.length > 1) {
                if (args[1].equals("-r")) {
                    XmlRpc xmlrpc = new XmlRpc("requestRetained");
                    xmlrpc.execute();
                } else if (args[1].equals("-n")) {
                    Thread th = new Thread(new FirstRunWizard());
                    th.start();
                }
            } else {
                launch(InstaDiscApp.class, args);
            }
        } else {
            System.out.println("Oops, you seem to be running this application incorrectly. Try running it using the startup script provided.");
        }
    }
}
