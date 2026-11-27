<?php

include_once(dirname(__FILE__) . "/cabecera.php");

//controlador
$barraUbi =
    [
        [
            "nombre" => "Inicio",
        ]
    ];

//dibuja la plantilla de la vista
inicioCabecera("INICIO");
cabecera();
finCabecera();
inicioCuerpo("INICIO", $barraUbi);
cuerpo($acceso);  //llamo a la vista
finCuerpo();
// **********************************************************


//vista
function cabecera() {}

//vista
function cuerpo(Acceso $acceso)
{
?>
    <br>
    <h3>Bienvenido.</h3>
    <!-- Si no se ha iniciado sesión -->
    <?php if (!$acceso->hayUsuario()) { ?>
        <p>Página de inicio de la aplicación.</p>
        <br>
    <?php } ?>

    <!-- Si se ha iniciado sesión -->
    <?php if ($acceso->hayUsuario()) { ?>
        <a href="/aplicacion/personalizar/index.php">Ir a Página Principal</a><br>
        <a href="/aplicacion/texto/verTextos.php">Ir a Textos</a><br>
    <?php } ?>

    <!-- Solo si se ha iniciado sesión con un usuario con permiso 1 -->
    <?php if ($acceso->puedePermiso(1)) { ?>
        <a href="/aplicacion/personalizar/personalizar.php">Ir a Personalizar Página</a><br>
    <?php } ?>

<?php
}
