/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc.FirstRun;

import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.DownloadItem.DownloadItemModeTest;
import javax.swing.JDialog;
import javax.swing.JFrame;

/**
 *
 * @author hatkirby
 */
public class FirstRunWizard implements Runnable {

    public void run()
    {
        Step1 s1 = new Step1(new JFrame(), true);
        s1.setVisible(true);
        if (StepEndResults.ok)
        {
            StepEndResults.ok = false;
            JDialog s2;
            
            if (StepEndResults.hasAccount)
            {
                s2 = new Step2(new JFrame(), true);
            } else {
                s2 = new Step2A(new JFrame(), true);
            }
            
            s2.setVisible(true);
            if (StepEndResults.ok)
            {
                StepEndResults.ok = false;
                DownloadItemModeTest dIMT = new DownloadItemModeTest(new JFrame(), true);
                dIMT.setVisible(true);
                if (StepEndResults.ok)
                {
                    StepEndResults.ok = false;
                    Step3 s3 = new Step3(new JFrame(), true);
                    s3.setVisible(true);
                    
                    Wrapper.setConfig("initCheck", "done");
                    Wrapper.setConfig("itemBufferSize", "10");
                    Wrapper.setConfig("verIDBufferSize", "10000");
                    Wrapper.setConfig("nextFilterID", "0");
                    Wrapper.setConfig("ipCheckValue", "1");
                    Wrapper.setConfig("ipCheckUnit", "day");
                    Wrapper.setConfig("useUnreadFlag", "true");
                }
            } else {
                run();
            }
        }
        System.exit(0);
    }
    
}
