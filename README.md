InstaDisc
=========

To quote the original InstaDisc wiki:

> InstaDisc is a productivity-increasing notification system. It is designed to allow you to quickly know about something without disrupting you from whatever you are doing.

In my early teens, I had an interest in being able to be notified in real-time of updates to websites, (more specifically, though, posts to my phpBB web forum which was mainly used by my friends) and I was disappointed with the long polling time that RSS had. This was before the days when push notifications were a commonplace thing. I came up with a three-tiered system that would allow people to receive push notifications on websites they were subscribed to. Services that wished to provide push notifications would provide a subscription file on their website that people would download and open with their InstaDisc client. The client would record the information in the subscription and forward it to the centralized InstaDisc server, recording that the client wished to receive the relevant push notifications for that service. Finally, when the service wanted to push a notification, it would send an XML-RPC request to the centralized server, which would then forward the notification to all of the subscribed clients.

I had implemented a small PHP library that services were to use to provide push notifications, as well as a few plugins for popular PHP packages such as phpBB, Wordpress and Mediawiki. The centralized server was written in PHP, and the client was written in Java. I did a weird thing where, instead of using an encrypted connection between each of the tiers of the process, packets contained a "Verification" field which was a hash of the end user's username, password and an autoincrementing ID which was also included in the packet. The client would record that the ID had been used and refuse to accept any new packets using that same ID for a period of time. In the case of private subscriptions, the packets themselves were also encrypted.

The original project had a variety of strange features including "categories" of notifications, being able to filter notifications based on arbitrary fields for each subscription, private subscriptions (for things like getting push notifiactions for your emails or private messages on a web forum), and being able to request from the centralized server retained notifications that could not be delivered to the client at time of notification.

The main problem the original project had was that it relied on the web-visibility of all of the end-user clients, which was not a remotely reliable assumption. A reboot of the project involved a C++ backend and using a heartbeat connection to download the notifications from the server, but was never completed after a loss in interest.
