<?php

// PÁGINA QUE INICIA LA SESIÓN DEL USUARIO

// Abre la sesión y crea $acl y $acceso
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Controlador
$barraUbi =
    [
        [
            "nombre" => "Inicio",
            "enlace" => "/index.php"
        ],
        [
            "nombre" => "Iniciar Sesión"
        ]
    ];

// Array para almacenar los errores del formulario
$arrayErrores = [];

// Variable que se escribe en el formulario, sirve para
// guardar el nick si el usuario introduce la contraseña mal
$nickForm = "";

// Si ya hay un usuario (ya se ha iniciado sesión)
if ($acceso->hayUsuario()) {
    header("Location: /index.php"); // Redirigir a index.php
    exit;
}

// Formulario
if (isset($_POST["user"]) && isset($_POST["password"])) {
    $user = mb_strtolower(mb_trim($_POST["user"]));
    $password = $_POST["password"];

    $nickForm = $user; // Guardar en la variable para el formulario

    // Si es válido
    if ($acl->esValido($user, $password)) {
        $cod = $acl->getCodUsuario($user);
        $nombre = $acl->getNombre($cod);
        $permisos = $acl->getPermisos($cod);

        if ($acceso->registrarUsuario($user, $nombre, $permisos)) {
            header("Location: /index.php");
            exit;
        } else {
            $arrayErrores["Login"][] = "No se ha podido iniciar la sesión";
        }
    } else {
        // Si no es válido, da error
        $arrayErrores["Login"][] = "Credenciales incorrectas";
    }
}

// Dibuja la plantilla de la vista
inicioCabecera("INICIAR SESIÓN");
cabecera();
finCabecera();
inicioCuerpo("INICIAR SESIÓN", $barraUbi);
cuerpo($arrayErrores, $nickForm);  //llamo a la vista
finCuerpo();
// **********************************************************


// Vista
function cabecera() {}

// Vista
function cuerpo(array $arrayErrores, string $nickForm) // Añadir aqui las variables
{
?>
    <br>
    <h3>Inicio de sesión.</h3>

    <?php
    formulario($arrayErrores, $nickForm); // Mostrar el formulario
    ?>

<?php

}

// Aqui escribiremos las funciones

// FUNCIONES

/**
 * Función que muestra el formulario para iniciar sesión
 * @param array $errores Array con los errores del formulario
 * @param string $nick Cadena con el nick que ha introducido el usuario (por defecto "")
 * 
 * @return void No devuelve nada
 */
function formulario(array $errores, string $nick): void
{
    if ($errores) { // Mostrar los errores
        echo "<div class='error'>";
        foreach ($errores as $clave => $valor) {
            foreach ($valor as $error)
                echo "$clave => $error<br>" . PHP_EOL;
        }
        echo "</div>";
    }
?>
    <form action="" method="post">
        <input name="user" type="text" placeholder="Usuario" value="<?= htmlspecialchars($nick) ?>">
        <br>
        <input name="password" type="password" placeholder="Contraseña">
        <br>
        <button type="submit">Iniciar sesión</button>
    </form>
<?php
}
