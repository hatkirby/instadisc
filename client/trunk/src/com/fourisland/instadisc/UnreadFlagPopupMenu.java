package com.fourisland.instadisc;

import com.fourisland.instadisc.Database.Item;
import com.fourisland.instadisc.Database.Wrapper;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.JList;
import javax.swing.JMenuItem;
import javax.swing.JPopupMenu;

public class UnreadFlagPopupMenu extends JPopupMenu
{
    private Item item;
    
    public UnreadFlagPopupMenu(Item item)
    {
        super();
        this.item = item;
        
        JMenuItem action = new JMenuItem("Mark as " + (item.getUnread() ? "Read" : "Unread"));
        action.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent arg0) {
                flipUnreadFlag();
            }
        });
        
        add(action);
    }
    
    private void flipUnreadFlag()
    {
        Wrapper.setUnreadFlagItem(item.getID(), !item.getUnread());
        
        ((InstaDiscView) InstaDiscApp.getApplication().getMainView()).refreshItemPane();
    }
}