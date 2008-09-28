package com.fourisland.instadisc.DownloadItem;

public interface DownloadItemMode
{
    public void modeInitalize();
    public void modeDeinitalize();
    
    public void requestRetained();
    public void sendItem(int id);
    
    public int setTimer();
    public void timerTick();
}