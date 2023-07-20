<?php

require_once 'plugins/login-servers.php';

$servers = json_decode($_ENV['LOGIN_SERVERS'], true);

if (empty($servers)) {
    return;
}

return new AdminerLoginServers($servers);
