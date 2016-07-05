# PHP-Dijkstras-Port-Mapping
Introduction
------------
This simple local web app that can run on localhost is built using PHP Zend Framework and MySQL. Its main function is finding a specified Data Port in ECERF building and drawing the shortest path from the floor’s main entrance to the destination (the data port). You also can get a data post list for a specified room. The Add Path Nodes subpage is used to create path nodes and add an edge of node to node in case some renovation will block the hallway in the future.  

WEB SERVER SETUP FOR THE APP
------------
### Apache Setup

To use Apache, setup a virtual host to point to the public/ directory of the project. Append virtual host info in file “httpd-vhosts.conf “(the file is typically located at c:\Apache24\conf\extra). It should look something like below:
```
<VirtualHost portmap.localhost.com:8080>
    ServerName portmap.localhost.com
    DocumentRoot C:/WebApp/port_map_php/public
    SetEnv APPLICATION_ENV development
    <Directory "C:/WebApp/port_map_php/public">
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
        <IfModule mod_authz_core.c>
        Require all granted
        </IfModule>
    </Directory>
</VirtualHost>
```
### Microsoft Internet Information Services (IIS)
•	Open notepad as administrator user
•	Add an entry to your hosts (file) like – “127.0.0.2 portmap.localhost.com” [one host in each line]. The hosts file is typically located at C:\WINDOWS\system32\drivers\etc\hosts
•	Now save the hosts file and close
•	Last create an entry in your IIS by following http://support.microsoft.com/kb/816576 with the above host name, i.e. portmap.localhost.com. Set the physical path to point to port_map_php\public directory

MySQL Database
------------
This web app is using MySQL, via PHP’s PDO driver, so run SQL script “dataport_phpMyAdmin” (the file is located at \ port_map_php\module\Dataport\data) to import “dataport” database. 
Then modify the username and password of MySQL in file “local.php “(the file is located at \port_map_php\config\autoload  ) to access the database. It should look something like below:
return array(
	'db' => array(
		'username'         => 'guest',
        	'password'         => '',		
	),
);

PRESENTATION OF THE APP
------------
