# Adminer for Azure MySQL Single & Flexible Server

[![Docker Pulls](https://badgen.net/docker/pulls/assoconnect/adminer-azure?icon=docker&label=pulls)](https://hub.docker.com/r/assoconnect/adminer-azure/)
[![Docker Stars](https://badgen.net/docker/stars/assoconnect/adminer-azure?icon=docker&label=stars)](https://hub.docker.com/r/assoconnect/adminer-azure/)
[![Docker Image Size](https://badgen.net/docker/size/assoconnect/adminer-azure?icon=docker&label=image%20size)](https://hub.docker.com/r/assoconnect/adminer-azure/)

This image is based on the official [Adminer Docker image](This image based on the official Adminer image ships the required setup to connect with SSL to an Azure MySQL Single or Flexible Server).

A daily build is available on the [Docker Hub](https://hub.docker.com/r/assoconnect/adminer-azure).

## Login SSL
This image ships with the required setup to connect with SSL to an Azure MySQL Single or Flexible Server.) ships the required setup to connect with SSL to an Azure MySQL Single or Flexible Server
* https://learn.microsoft.com/en-us/azure/mysql/single-server/how-to-configure-ssl
* https://learn.microsoft.com/en-us/azure/mysql/flexible-server/how-to-connect-tls-ssl

## Login Servers
This images also leverage the `login-servers.php` plugin to provide a predefined list of servers.

Define the env variable `LOGIN_SERVERS` as JSON-encoded list of servers
```
{
  "server description": {
    "server": "[host]",
    "driver": "[server|pgsql|sqlite|...]"
  },
  ...
}
```
_Note that the `server` driver is Adminer's internal name for MysQL._
