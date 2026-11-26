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
cuerpo();  //llamo a la vista
finCuerpo();
// **********************************************************


//vista
function cabecera() {}

//vista
function cuerpo()
{
?>
    <br>
    <h3>Bienvenido.</h3>
    <p>Página de inicio de la aplicación.</p>
<?php
}
