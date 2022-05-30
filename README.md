Para la instalacion y ejecucion del proyecto en su ordenador podria descargar el proyecto, primeramente tiene que tener instalado composer, PHP7+, Laravel y node para instalar las librerias npm.

Los enlaces de descarga los dejaré por acá: composer: https://getcomposer.org/download/ PHP 7+: https://www.php.net/downloads Node JS: https://nodejs.org/es/download/ Laravel: https://laravel.com/docs/9.x/installation

Y luego descargar los recursos necesarios para que funcione correctamente:

-composer install

-npm install

y con esos dos comandos podremos instalar los paquetes que neceitamos.

para arrancar el proyecto ejecutamos en nuestra terminal el comando:

-php artisan serve.

y eso bastaría para arrancar el proyecto.

La aplicación esta deployada en heroku aunque algunas rutas no funcionaran ya que estan dentro del jwt.

https://exercisediimo.herokuapp.com/api/v1/diimo/

este es el endpoint principal.