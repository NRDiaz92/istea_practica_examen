# Ejercicios:

### 1) Test-api:

#### a) La imagen (A elección) debe contener los siguientes paquetes instalados:

~~~
    - apt-utils
    - curl
    - redis-tools
    - postgresql-client
    - nginx
    - php7.4
    - php7.4-fpm
    - php7.4-pgsql
~~~

##### Pasos para instalar php (Basarse en la siguiente documentación)
```https://www.pixelspress.com/how-to-install-php-fpm-on-ubuntu/```

##### Consejo: Agregar estos esta configuración para hacer no interactiva la instalación de paquetes durante el build.
```APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn```
```DEBIAN_FRONTEND=noninteractive```

#### b) Se tienen que agregar los archivos de configuración en las siguientes rutas:

~~~
Carpeta Local: test-api/nginx
Carpeta en contenedor: /etc/nginx
~~~
~~~
Carpeta en local: test-api/php
Carpeta en contenedor: /etc/php
~~~
~~~
Archivo en local: test-api/site/index.php
Archivo en contenedor: /var/app/index.php
~~~
~~~
Archivo en local: test-api/site/init.sh
Archivo en contenedor: /init.sh
~~~

#### c) El workdir debe ser la ruta donde se encuentra la aplicación de php

#### d) Con el siguiente comando se configura correctamente los archivos de nginx a utilizar como configuración del servicio:
```rm -rf /etc/nginx/sites-enabled && ln -sf /etc/nginx/sites-available /etc/nginx/sites-enabled```

### 2) Test-worker:

#### a) La imagen (A elección) debe contener los siguientes paquetes instalados:

~~~
    - apt-utils
    - curl
    - redis-tools
    - postgresql-client
~~~

##### Consejo: Agregar estos esta configuración para hacer no interactiva la instalación de paquetes durante el build.
```APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn```
```DEBIAN_FRONTEND=noninteractive```

#### b) Se tienen que agregar los archivos de configuración en las siguientes rutas:
~~~
Archivo en local: test-worker/site/init.sh
Archivo en contenedor: /init.sh
~~~
#### c) El workdir no es necesario definirlo

### 3) Test-Redis:

#### a) Se debe usar la imagen redis:latest

#### b) Se tienen que agregar los archivos de configuración en las siguientes rutas:
~~~
Archivo en local: test-redis/redis.conf
Archivo en contenedor: /usr/local/etc/redis/redis.conf
~~~
### 4) Docker-compose:

#### a) Tiene que haber 4 servicios creados:

~~~
test-api: 
    imagen: debe hacer build del dockerfile creado para test-api
    puertos: tiene que exponer el puerto 80 del contenedor en un puerto a elección del host
    entrypoint: Tiene que ejecutar el siguiente comando: /bin/bash -c 'bash /init.sh START_API'
    variables_generales: Tiene que utilizar las variables definidas abajo
    healthcheck: definir el siguiente comando: "CMD-SHELL", "bash -x init.sh API_READY"
    Servicios a conectar: Worker, Redis y PGSQL
~~~
~~~
test-worker: 
    imagen: debe hacer build del dockerfile creado para test-worker
    entrypoint: Tiene que ejecutar el siguiente comando: /bin/bash -c 'bash /init.sh START_WORKER && bash''
    variables_generales: Tiene que utilizar las variables definidas abajo
    healthcheck: definir el siguiente comando: "CMD-SHELL", "bash -x init.sh WORKER_READY"
    Servicios a conectar: Redis y PGSQL
~~~
~~~    
test-redis: 
    imagen: debe hacer build del dockerfile creado para test-redis
    variables_propias: Tiene que tener la siguiente variable definida: REDIS_REPLICATION_MODE=master
    variables_generales: Tiene que utilizar las variables definidas abajo
    healthcheck: definir el siguiente comando: "CMD-SHELL", "redis-cli -a ${REDIS_PASSWORD} ping"
~~~
~~~    
test-pgsql: 
    imagen: utilizar la imagen oficial de postgres (Buscar en dockerhub)
    variables_generales: Tiene que utilizar las variables definidas abajo
~~~

#### b) Generar una red que interconecte todos los servicios

##### Variables generales a definir
~~~
POSTGRES_PASSWORD=2miYFGVoHMZrvZAh
POSTGRES_USER=postgres
POSTGRES_DB=istea_db
POSTGRES_TABLE=istea_table
POSTGRES_HOST=test-pgsql
REDIS_PASSWORD="YjtfdLD6arl3rL3h"
~~~

### Datos importantes:

#### a) Se puede probar el servicio ingresando en localhost:80 y me debería mostrar los datos de conexión a la base de datos de pgsql (dichos datos se traen de redis, por lo que para funcinoar tiene que estar activa la conexión a redis)

#### b) Para ejecutar desde cero se puede utilizar el script start_test, ejecutándolo de la siguiente manera: sh start_test