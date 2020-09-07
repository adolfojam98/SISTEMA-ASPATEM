# SISTEMA-ASPATEM
Este sistema esta pensado para una organización de tenis de mesa sin fines de lucro(en desarrollo)


Para probarlo seguir los siguientes pasos:

Instalar composer(manejador de paquetes php), se puede encontrar en el siguiente link : https://getcomposer.org/

Instalar Node js. Lo conseguis en el siguiente link : https://nodejs.org/es/
Tener instalado algun Sistema Gestor de Base de Datos(preferiblemente para manejar motor MySQL pero si se tiene otro no hay inconveniente

Instalar LARAVEL(framework de php) : ejecutar el siguiente comando en un terminal : composer global require laravel/installer

Si cuentan con Git instalado , en la terminal ejecutan eñ siguiente comando para traer el proyecto : git clone https://github.com/adolfojam98/SISTEMA-ASPATEM

entran al proyecto con la misma teminal poniendo cd SISTEMA-ASPATEM

Cuando estemos en la carpeta del proyecto ejecutar los siguientes comandos en orden:

composer install
npm install
cp .env.example .env
php artisan key:generate

Ahora lo siguiente es abrir el proyecto en un esitor de codigo
que qq
Buscamos el archivo .env que se encuentra en la raiz

Luego modificamos las siguientes lineas:

DB_CONNECTION=mysql //Aca indicamos que motor de base de datos vamos a usar

DB_DATABASE=lraravel //Aca indicamos que base de datos vamos a usar(se tiene que crear previamente)

DB_USERNMAE=root //Aca indicamos que usuario se va a conectar

DB_PASSWORD= //Aca indicamos la contraseña del usuario

Luego de esto, teniendo la base de datos ya funcionando, tenemos que migrar las tablas, para eso ejecutamos el comando: php artisan migrate --seed  (la bandera --seed sirve para que cargue datos de prueba)

Por ultimo ejecutamos el siguiente comando para dar de alta el proyecto en un servidor local : php artisan serve


