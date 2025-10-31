<?php

$servers = json_decode($_ENV['LOGIN_SERVERS'], true);
if ([] === $servers) {
    return new stdClass();
}

require_once 'plugins/login-servers.php';


return new AdminerLoginServers($servers);
