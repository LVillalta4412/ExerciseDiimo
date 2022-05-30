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

Tambien mencionar que la parte de envio de correos estoy utilizando Mailtrap para pruebas, Asi que los mensajes me llegan dentro de la plataforma para obtener el enlace de recuperacion de password. Si en caso se necesita obtener acceso para una mejor verificacion, notificarmelo por favor paa brindar credenciales.

migrations database:

2014_10_12_000000_create_users_table

2014_10_12_100000_create_password_resets_table

2019_08_19_000000_create_failed_jobs_table

2019_12_14_000001_create_personal_access_tokens_table

2022_05_27_171931_create_products_table

La database y los endpoints de postman estan en carpeta en este proyecto. Est
La database en una carpeta llamada bd y la api en EndpointsPostman.
