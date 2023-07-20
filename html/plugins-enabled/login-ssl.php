<?php

require_once 'plugins/login-ssl.php';

return new AdminerLoginSsl([
    'ca' => __DIR__ . '/CombinedRoot.crt.pem',
]);
