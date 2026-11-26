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
            "nombre" => "Login"
        ]
    ];

$arrayErrores = [];
$nickForm = "";

// Si hay Usuario
if ($acceso->hayUsuario()) {
    header("Location: /index.php");
    exit;
}

// Formulario
if (isset($_POST["user"]) && isset($_POST["password"])) {
    $user = mb_trim($_POST["user"]);
    $password = $_POST["password"];

    if ($acl->esValido($user, $password)) {
        $cod = $acl->getCodUsuario($user);
        $nombre = $acl->getNombre($cod);
        $permisos = $acl->getPermisos($cod);

        if ($acceso->registrarUsuario($user, $nombre, $permisos)) {
            header("Location: /aplicacion/principal/index.php");
            exit;
        } else {
            $arrayErrores["Login"][] = "No se ha podido iniciar la sesión";
        }
    } else {
        $arrayErrores["Login"][] = "Credenciales incorrectas";
    }
}

//dibuja la plantilla de la vista
inicioCabecera("LOGIN");
cabecera();
finCabecera();
inicioCuerpo("LOGIN", $barraUbi);
cuerpo($arrayErrores, $nickForm);  //llamo a la vista
finCuerpo();
// **********************************************************


//vista
function cabecera() {}

//vista
function cuerpo(array $arrayErrores, string $nickForm) // Añadir aqui las variables
{
?>
    <br>
    <h3>Login.</h3>

    <?php
    formulario($arrayErrores, $nickForm);
    ?>

<?php

}

// Aqui escribiremos las funciones

// FUNCIONES

/**
 * Función que muestra el formulario para iniciar sesión
 * @param array $errores Array con los errores del formulario
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
