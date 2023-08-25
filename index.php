<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

switch ($request) {
    case '':
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;

    case '/servidores':
        require __DIR__ . $viewDir . 'servidores.php';
        break;

    case '/hosting-web':
        require __DIR__ . $viewDir . 'hosting.php';
        break;
    case '/soluciones/almacenamiento-en-nube':
        require __DIR__ . $viewDir . 'almacenamiento-en-nube.php';
        break;
    case '/soluciones/correo-corporativo':
        require __DIR__ . $viewDir . 'correo-corporativo.php';
        break;
    case '/acerca-de':
        require __DIR__ . $viewDir . 'acerca-de.php';
        break;
    case '/certificados-ssl':
        require __DIR__ . $viewDir . 'ssl.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}