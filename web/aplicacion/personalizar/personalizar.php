<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");

// Sin usuario redirecciono al login
if (!$acceso->hayUsuario()) {
    header("Location: /aplicacion/acceso/login.php");
    exit;
}

// Con usuario pero sin el permiso 1 redirecciono a la página de error
if (!$acceso->puedePermiso(1)) {
    paginaError("No tienes permisos para acceder a esta página", []);
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
            "nombre" => "Personalizar"
        ]
    ];

if (isset($_POST["fondo"])) {
    $indice = $_POST["fondo"];
    if (array_key_exists($indice, COLORESFONDO)) {
        setcookie("fondo", $indice, time() + 60 * 60 * 24 * 2, "/");
    }
}

if (isset($_POST["texto"])) {

    $indice = $_POST["texto"];

    if (array_key_exists($indice, COLORESTEXTO)) {
        setcookie("texto", $indice, time() + 60 * 60 * 24 * 2, "/");
    }
}

// Color de fondo
if (isset($_COOKIE['fondo'])) {
    $fondo = $_COOKIE['fondo'];
} else {
    $fondo = "white"; // Por defecto "blanco"
}

// Color de texto
if (isset($_COOKIE['texto'])) {
    $texto = $_COOKIE['texto'];
} else {
    $texto = "black"; // Por defecto "negro"
}

// Recargar la página para actualizar las cookies
if (isset($_POST["fondo"]) || isset($_POST["texto"])) {
    header("Location: personalizar.php");
    exit;
}

//dibuja la plantilla de la vista
inicioCabecera("PERSONALIZAR");
cabecera($fondo, $texto);
finCabecera();
inicioCuerpo("PERSONALIZAR", $barraUbi);
cuerpo();  //llamo a la vista
finCuerpo();
// **********************************************************


//vista
function cabecera(string $fondo, string $texto)
{
?>
    <style>
        #documento {
            background-color: <?= $fondo ?>;
            color: <?= $texto ?>;
        }
    </style>
<?php
}

//vista
function cuerpo() // Añadir aqui las variables
{
?>
    <br>
    <h3>Personalizar Página Principal de la Relación 8.</h3>
    <a href="../personalizar/index.php">← Ir a Página Principal</a>
    <br>
<?php

    formulario(); // Mostrar formulario
}

// Aqui escribiremos las funciones

// FUNCIONES

/**
 * Función que muestra el formulario para cambiar los colores de fondo y texto
 *
 * @return void No devuelve nada
 */
function formulario(): void
{
?>
    <form action="" method="post">
        <h4>Colores de fondo</h4>
        <select name="fondo" id="fondo">
            <?php foreach (COLORESFONDO as $key => $value) {
                $color = mb_strtoupper($value["nombre"]);
                if ($key == $_COOKIE['fondo']) {
                    echo "<option value = '$key' selected>$color</option>";
                } else {
                    echo "<option value = '$key'>$color</option>";
                }
            } ?>
        </select>
        <br>
        <h4>Colores de texto</h4>
        <select name="texto" id="texto">
            <?php foreach (COLORESTEXTO as $key => $value) {
                $color = mb_strtoupper($value["nombre"]);
                if ($key == $_COOKIE['texto']) {
                    echo "<option value = '$key' selected>$color</option>";
                } else {
                    echo "<option value = '$key'>$color</option>";
                }
            } ?>
        </select>
        <br><br>
        <button type="submit">Aplicar cambios</button>
    </form>
<?php
}
