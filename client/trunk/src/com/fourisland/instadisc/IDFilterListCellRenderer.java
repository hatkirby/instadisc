/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Filter;
import java.awt.Component;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.ListCellRenderer;

/**
 *
 * @author hatkirby
 */
class IDFilterListCellRenderer extends JLabel implements ListCellRenderer {

    public Component getListCellRendererComponent(JList arg0, Object arg1, int arg2, boolean arg3, boolean arg4) {
        Filter filter = (Filter) arg1;
        
        if (filter.getID() == -65536)
        {
            this.setText("Add new filter");
        } else {
            this.setText(filter.getField() + " " + (filter.getEqual() ? "=" : "!=") + " " + filter.getTest());
        }
        
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
