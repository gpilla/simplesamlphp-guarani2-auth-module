# simplesamlphp-guarani2-auth-module

Modulo de autenticación contra base [SIU-Guaraní 2](http://www.siu.edu.ar/siu-guarani/) para [SimpleSAMLphp](https://simplesamlphp.org/).

A pesar de que el SIU-Guarani 2 tiene un futuro acotado, y se recomienda migrar a SIU-Guarani 3, comparto esta pieza de código que estuvo funcionando con exito algunos meses. Tambien está disponible la versión para SIU-Guaraní 3 (https://github.com/gpilla/simplesamlphp-guarani3-auth-module)

## Instalación

Subir la carpeta "guarani" a {ruta al simplesamlphp}/modules/

## Configuración básica

En el archivo {ruta al simplesamlphp}/config/authsources.php, agregar las siguientes lineas:

```php
<?php

$config = array(

    // ... algo antes ...

    'guarani' => array(
        'guarani:GuaraniAuth',
        'LogonId' => 'unUsuarioMuySeguro',
        'pwd' => 'unaClaveMuySegura',
        'database' => 'elNombreDeLaBase',
        'host' => '192.168.0.2',
        'server' => 'descripcionServerDelInformix',
    ),

    // ... algo despues ...

);

```

Nota: Esta ultima configuración puede repetirse para tener multilpes instancias de Guarani (en caso de facultades, o en casos de ambientes de producción y testing)

Probar la autenticación desde la pantalla de tests, y configurar en {ruta al simplesamlphp}/metadata/saml20-idp-hosted.php

```php
<?php

    $metadata['__DYNAMIC:1__'] = array(

        // ... algo antes ...

        /*
         * Authentication source to use. Must be one that is configured in
         * 'config/authsources.php'.
         */
        'auth' => 'guarani',

        // ... algo despues ...

    );

```
