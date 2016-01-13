# simplesamlphp-guarani2-auth-module

Modulo de autenticación contra base [SIU-Guaraní 2](http://www.siu.edu.ar/siu-guarani/) para [SimpleSAMLphp](https://simplesamlphp.org/).

A pesar de que el SIU-Guarani 2 tiene un futuro acotado, y se recomienda migrar a SIU-Guarani 3, comparto esta pieza de código que estuvo funcionando con exito algunos meses.

## Instalación

Subir la carpeta "guarani" a {ruta al simplesamlphp}/modules/

## Configuración básica

En el archivo {ruta al simplesamlphp}/config/authsources.php, agregar las siguientes lineas:

```php
<?php

$config = array(

    ...

    'guarani' => array(
        'guarani:GuaraniAuth',
        'LogonId' => 'unUsuarioMuySeguro',
        'pwd' => 'unaClaveMuySegura',
        'database' => 'elNombreDeLaBase',
        'host' => '192.168.0.2',
        'server' => 'descripcionServerDelInformix',
    ),

    ...

);

```
