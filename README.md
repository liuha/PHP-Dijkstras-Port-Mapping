# PHP-Dijkstras-Port-Mapping
Introduction
------------
This simple local web app that can run on localhost is built using PHP Zend Framework and MySQL. Its main function is finding a specified Data Port in ECERF building and drawing the shortest path from the floor’s main entrance to the destination (the data port). You also can get a data post list for a specified room. The Add Path Nodes subpage is used to create path nodes and add an edge of node to node in case some renovation will block the hallway in the future.  

WEB SERVER SETUP FOR Windows OS
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
- Open notepad as administrator user
- Add an entry to your hosts (file) like – “127.0.0.2 portmap.localhost.com” [one host in each line]. The hosts file is typically located at C:\WINDOWS\system32\drivers\etc\hosts
- Now save the hosts file and close
- Last create an entry in your IIS by following http://support.microsoft.com/kb/816576 with the above host name, i.e. portmap.localhost.com. Set the physical path to point to port_map_php\public directory

MySQL Database
------------
This web app is using MySQL, via PHP’s PDO driver, so run SQL script “dataport_phpMyAdmin” (the file is located at \ port_map_php\module\Dataport\data) to import “dataport” database. 

Then modify the username and password of MySQL in file “local.php “(the file is located at \port_map_php\config\autoload  ) to access the database. It should look something like below:
```
return array(
	'db' => array(
		'username'         => 'guest',
		'password'         => '',		
	),
);
```
PRESENTATION OF THE APP
------------
- Search by Data Port Number

The default floor number is 3. Or you can select desired level from left side level bar. 
If the desired data port is exist, its detail will be displayed, a blinking circle will be printed overplay on the floor map. Meanwhile if the data port’s location is known, the shortest path from the floor’s main entrance to the destination will be drew. 

If you think that the coordinate of the data port is wrong, you can update it by clicking update  button. Then click the new coordinate on the map. 

- Search by Room Number

Inputting the Rom number, a relevant data port list will be displayed. Otherwise an error message will be display. 

- Add Path Nodes
 
This function will be used only when a renovation blocks the hallway in the future and you need to create a node to draw the shortest path. Nodes for existing data ports already exist in database. In addition, these node coordinates will be updated automatically when you update the corresponding data port coordinates. 

Selecting a level, entering a node name, clicking on the map to get coordinate and clicking AddNote are all you need to do to add a path node. 

- Manage Edges
 
If you plan to add or delete a lot of edges at the same time, we strongly suggest you use MySQL query/queries script. It is must easier and timesaving. In the database, an edge is undirected. Therefore A=>B or B => A is equivalent to A <=>B. 
Selecting floor and then selecting Start Node and End Node are all you need to do to add or delete an edge. 

