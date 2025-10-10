<?php

require_once 'plugins/login-ssl.php';

return new AdminerLoginSsl([
    'ca' => 'certificates/CombinedRoot.crt.pem',
]);
