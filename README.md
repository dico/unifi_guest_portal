# Unifi Customer Guest Portal

### What is this?
This is part of a larger guest portal. Part of this code and database refers to AD authentication and room-login, 
which is not included in this code. Some of this code is either commented out or not in use.

The portal is tested with SMS authentication, with SMS API from Diafaan and Clickatell. 
SMS auth should work out-of-box with this code, but requires you to have a working SMS server with HTTP connection (e.g. Clickatell).

All config-settings are working settings in our demo-environment.

This code is ment for testing purposes, and you should not use it in a production environment without web-security knowledge. 


### Get started
You will need a webserver with Apache, PHP and MySQL.

1. Import the database from the included database.sql file.
2. Copy files to your server.
3. Copy the config-sample.php to config.php, and change settings to your own environment.

**Requirements**
* PHP Curl


**Unifi controller settings**

In our demo environment, the Unifi controller runs version 5.6.26. 
The Guest Control settings are set to "External portal server" with IP to the webserver.

**Redirects**

When the device tries to connect to the guest network, the user will be redirected to server configured in the Unifi controller, 
with the following path /guest/s/SITENAME/.

This code includes a simple redirect in this folder. 
You will probably need to check this index.php file, so it redirects correct to your guestportal-webpage.

If you have multiple sites, you need to create a folder for each site, with the same index.php file for redirect.
Or you can build some logic that autocreate these folders or auto-redirects.
The redirect needs to include the parameters added by the Unifi controller like ?id=00:00:00:00:00:00&ap=00:00:00:00:00:00&t=1525480453&url=http://google.no%2f&ssid=GuestNetwork.


**Guest portal auth**

The custom guestportal is just a simple webpage that can authenticate a user by username and password, SMS (included in code), Facebook, Active Directory or whatever you want.
If the webpage authenticate the user, it will send the device MAC-address and number of minutes to the Unifi controller, through their API.

**Firewall**

The ports are defined in config.php. By default Unifi controller uses port 8443. The webserver needs access to send web-request to the Unifi controller on this port.