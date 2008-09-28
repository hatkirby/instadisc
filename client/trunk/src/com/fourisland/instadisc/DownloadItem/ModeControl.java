package com.fourisland.instadisc.DownloadItem;

public class ModeControl implements DownloadItemMode
{
    public static final ModeControl INSTANCE = new ModeControl();
    private DownloadItemMode dim;
    
    public ModeControl()
    {
        Runtime.getRuntime().addShutdownHook(new Thread(new DeinitalizeModeThread()));
    }
    
    public void initalize(String dim) throws UnknownDownloadItemModeException
    {
        if (dim.equals("Push"))
        {
            this.dim = new PushMode();
        } else if (dim.equals("Pull"))
        {
            this.dim = new PullMode();
        } else {
            throw new UnknownDownloadItemModeException();
        }
    }

    public void modeInitalize() {
        dim.modeInitalize();
    }

    public void modeDeinitalize() {
        dim.modeDeinitalize();
    }
    
    public void requestRetained()
    {
        dim.requestRetained();
    }

    public int setTimer() {
        return dim.setTimer();
    }

    public void timerTick() {
        dim.timerTick();
    }
}