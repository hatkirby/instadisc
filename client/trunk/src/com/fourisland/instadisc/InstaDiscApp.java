/*
 * InstaDiscApp.java
 */
package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.FirstRun.FirstRunWizard;
import java.awt.TrayIcon;
import java.io.File;
import org.jdesktop.application.Application;
import org.jdesktop.application.SingleFrameApplication;

/**
 * The main class of the application.
 */
public class InstaDiscApp extends SingleFrameApplication {

    public static TrayIcon ti;

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
        File db = new File(System.getProperty("user.home") + File.separator + ".instadisc");
        if (!db.exists()) {
            db.mkdir();
        }

        Wrapper.init(db.getAbsolutePath());

        boolean notInit = false;
        try {
            if (!Wrapper.getConfig("initCheck").equals("done")) {
                notInit = true;
            }
        } catch (NullPointerException ex) {
            notInit = true;
        }
        
        if (notInit) {
            Thread th = new Thread(new FirstRunWizard());
            th.start();
        } else {
            if ((args.length > 0) && (args[0].equals("-r"))) {
                XmlRpc xmlrpc = new XmlRpc("requestRetained");
                xmlrpc.execute();
            } else {
                launch(InstaDiscApp.class, args);
            }
        }
    }
}
