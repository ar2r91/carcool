##INSTRUCCIONES PROYECTO

-Ejecutar en consola: composer update
<br>
-Verificar si existe el archivo .env
<br>
-Verificar si existe el APP KEY dentro del .env. En caso contrario ejecutar: php aritsan key:generate
<br>
-Crear base de datos en mysql llamado carcoll
<br>
-Añadir en database.php la siguiente conexion con el nombre de la base de datos, username y contraseña.
<br>
Asi:
<br>
'DB_CONNECTION_CARCOOL' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'carcool',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'port' => '3306',
            'strict' => false,
        ],
        
<br>
-ejecutar: php artisan migrate
<br>
-Ejecutar: php artisan serve
<br>
-Finalmente seguir instrucciones:
<br>
- /competitions - Obtendra el detalle de todas las ligas 
<br>
- /competitions/<competition_id> - Obtiene el detalle de una liga especifica (Equipos y jugadores que luego serán almacenados localmente)
<br>
- /team - Muestra todos los equipos que se han almacenado hasta el momento
<br>
- /team/<team_id> - Muestra el detalle de los equipo seleccionado
<br>
- /players -  Muestra todos los jugadores almacenados hasta el momento