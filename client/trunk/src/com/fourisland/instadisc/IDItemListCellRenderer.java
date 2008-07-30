/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Item;
import com.fourisland.instadisc.Database.Wrapper;
import com.fourisland.instadisc.Item.Categories.Category;
import java.awt.Color;
import java.awt.Component;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JList;
import javax.swing.ListCellRenderer;

/**
 *
 * @author hatkirby
 */
public class IDItemListCellRenderer extends JLabel implements ListCellRenderer {

    public Component getListCellRendererComponent(JList arg0, Object arg1, int arg2, boolean arg3, boolean arg4) {
        Item item = (Item) arg1;

        this.setIcon(Category.iconFromCategory(Wrapper.getSubscription(item.getSubscription()).getCategory()));
        this.setText("<HTML><I>" + Wrapper.getSubscription(item.getSubscription()).getTitle() + "</I><B>" + item.getTitle() + "</B> by " + item.getAuthor());

        /*if (item.getUnread()) {
            this.setBackground(Color.YELLOW);
        } else */{
            if (arg3) {
                this.setForeground(arg0.getSelectionForeground());
                this.setBackground(arg0.getSelectionBackground());
            } else {
                this.setForeground(arg0.getForeground());
                this.setBackground(arg0.getBackground());
            }
        }

        this.setOpaque(true);
        this.setFont(arg0.getFont());
        this.setEnabled(arg0.isEnabled());

        return this;
    }
}
