/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc.FirstRun;

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
            Step2 s2 = new Step2(new JFrame(), true);
            s2.setVisible(true);
            if (StepEndResults.ok)
            {
                StepEndResults.ok = false;
                Step3 s3 = new Step3(new JFrame(), true);
                s3.setVisible(true);
            }
        }
        System.exit(0);
    }
    
}
