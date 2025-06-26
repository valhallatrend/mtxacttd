<?php
function auth() {
    $res = file_get_contents('https://axslsp.onrender.com/index.php'); // Cambia por tu URL real
    $users = json_decode($res, true);

    $u = $_SERVER['PHP_AUTH_USER'] ?? '';
    $p = $_SERVER['PHP_AUTH_PW'] ?? '';

    foreach ($users as $c) {
        if ($c['u'] === $u && $c['p'] === $p) return true;
    }

    header('WWW-Authenticate: Basic realm="Acceso restringido"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso denegado';
    exit;
}
