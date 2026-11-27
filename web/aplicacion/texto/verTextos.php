<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");



// Si no hay usuario validado, mando al login
if (!$acceso->hayUsuario()) {
    $_SESSION["destino"] = $_SERVER["REQUEST_URI"];  // Guardo la página que quería ver
    header("Location: /aplicacion/acceso/login.php");
    exit;
}

//controlador
$barraUbi =
    [
        [
            "nombre" => "Inicio",
            "enlace" => "/index.php"
        ],
        [
            "nombre" => "Textos"
        ]
    ];

// Array para guardar los errores del formulario
$arrayErrores = [];

// Limpiar textos
if (isset($_POST['limpiar'])) {
    unset($_SESSION['textos']); // Borrar el array de la sesión

    // Limpiar POST
    header("Location: /aplicacion/texto/verTextos.php");
    exit();
}

// Sesión
if (isset($_SESSION['textos'])) { // Si la sesión existe
    $textos = $_SESSION['textos']; // Guardar el el array de la sesión en el array de la página
} else { // Si la sesión no existe
    $textos = []; // Nuestro array empieza vacío
}

// Añadir textos
if (isset($_POST["texto"])) {
    if ($_POST["texto"] === "") { // No se puede escribir cadenas vacías
        $arrayErrores["Texto"][] = "No puedes introducir una cadena vacía";
    } else {
        $textoNuevo = new RegistroTexto($_POST["texto"]); // Creamos un nuevo RegistroTexto

        $textos[] = $textoNuevo; // Guardar el objeto RegistroTexto

        $_SESSION['textos'] = $textos; // Guardar el array en la sesión

        // Limpiar POST
        header("Location: /aplicacion/texto/verTextos.php");
        exit();
    }
}

//dibuja la plantilla de la vista
inicioCabecera("TEXTOS");
cabecera();
finCabecera();
inicioCuerpo("TEXTOS", $barraUbi);
cuerpo($textos, $arrayErrores);  //llamo a la vista
finCuerpo();
// **********************************************************


//vista
function cabecera() {}

//vista
function cuerpo(array $textos, array $arrayErrores) // Añadir aqui las variables
{
?>
    <br>
    <h3>Textos.</h3>
<?php

    // Para ello, definir un formulario con un text donde se indica el texto a registrar y 
    // un botón registrar. En esta página aparecerá también un textarea en el que se mostrarán
    // todos los textos indicados por el usuario junto con la fecha/hora en la que lo introdujo y
    // un botón Limpiar para borrar todos los textos.

    formulario($textos, $arrayErrores); // Mostrar formulario
}

// Aqui escribiremos las funciones

// FUNCIONES

/**
 * Función para mostrar el formulario
 *
 * @param array $textos Array con nuestros textos
 * @param array $errores Array de errores
 * @return void No devuelve nada
 */
function formulario(array $textos, array $errores): void
{
    if ($errores) { // Mostrar los errores
        echo "<div class='error'>";
        foreach ($errores as $clave => $valor) {
            foreach ($valor as $error)
                echo "$clave => $error<br>" . PHP_EOL;
        }
        echo "</div>";
    }

    $contenidoArea = "";
    foreach ($textos as $value) {
        $cadena = $value->getCadena();
        $fecha = $value->getFechaHora()->format("d/m/Y");
        $hora = $value->getFechaHora()->format("H:i:s");

        $contenidoArea .= "$cadena - $fecha - $hora\n"; // Agregar las líneas al textArea
    }
?>
    <form action="" method="post">
        <textarea name="areaTextos" id="areaTextos" cols="52" rows="10" readonly><?php echo $contenidoArea; ?></textarea>
        <br><br>
        <input type="text" name="texto" size="50">
        <br>
        <button type="submit" name="registrar">Registrar texto</button>
        <button type="submit" name="limpiar">Limpiar</button>
    </form>
<?php
}
