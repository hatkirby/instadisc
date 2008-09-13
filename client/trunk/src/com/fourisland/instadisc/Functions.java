package com.fourisland.instadisc;

public class Functions {

    public static boolean xor(boolean x, boolean y) {
        return (x == y);
    }

    public static int max(int x, int y) {
        return (x > y ? x : y);
    }

    public static String padleft(String in, String pad, int len) {
        while (in.length() < len)
        {
            in = pad + in;
        }

        if (in.length() > len)
        {
            in = in.substring(0, len);
        }

        return in;
    }

    public static String reverse(String in) {
        String out = "";
        int i = 0;

        for (i = 0; i < in.length(); i++)
        {
            out = in.charAt(i) + out;
        }

        return out;
    }

    public static byte[] hexToBytes(String str) {
        if (str == null)
        {
            return null;
        } else if (str.length() < 2)
        {
            return null;
        } else if (str.length() < 2)
        {
            return null;
        } else
        {
            int len = str.length() / 2;
            byte[] buffer = new byte[len];
            for (int i = 0; i < len; i++)
            {
                buffer[i] = (byte) Integer.parseInt(str.substring(i * 2, i * 2 + 2), 16);
            }

            return buffer;
        }
    }

    public static String padright(String in, String pad, int len) {
        while (in.length() < len)
        {
            in += pad;
        }

        if (in.length() > len)
        {
            in = in.substring(0, len);
        }

        return in;
    }

    public static byte[] pad(byte[] buffer) {
        while (buffer.length % 16 != 0)
        {
            byte[] tmp = new byte[buffer.length + 1];
            int i = 0;
            for (i = 0; i < buffer.length; i++)
            {
                tmp[i] = buffer[i];
            }
            tmp[buffer.length] = 0;
            buffer = tmp;
        }
        return buffer;
    }

    public static String bytesToHex(byte[] buffer) {
        if (buffer == null)
        {
            return null;
        } else
        {
            StringBuilder result = new StringBuilder();
            for (int i = 0; i < buffer.length; i++)
            {
                String hex = Integer.toHexString(Integer.decode(new Byte(buffer[i]).toString()));
                result.append(padleft(hex.substring(max(hex.length() - 2, 0)), "0", 2));
            }

            return result.toString();
        }
    }
}
    