/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Subscription;
import java.awt.Component;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.ListCellRenderer;

/**
 *
 * @author hatkirby
 */
class IDSubscriptionListCellRenderer extends JLabel implements ListCellRenderer {

    public Component getListCellRendererComponent(JList arg0, Object arg1, int arg2, boolean arg3, boolean arg4) {
        this.setText(((Subscription) arg1).getTitle() + " (" + ((Subscription) arg1).getCategory() + ")");
        
        if (arg3) {
            this.setForeground(arg0.getSelectionForeground());
            this.setBackground(arg0.getSelectionBackground());
        } else {
            this.setForeground(arg0.getForeground());
            this.setBackground(arg0.getBackground());
        }

        this.setOpaque(true);
        this.setFont(arg0.getFont());
        this.setEnabled(arg0.isEnabled());

        return this;
    }
}
