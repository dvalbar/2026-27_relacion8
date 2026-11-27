<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");

//controlador
$barraUbi =
    [
        [
            "nombre" => "Inicio",
        ]
    ];

//dibuja la plantilla de la vista
inicioCabecera("RELACIÓN 8");
cabecera();
finCabecera();
inicioCuerpo("RELACIÓN 8", $barraUbi);
cuerpo();  //llamo a la vista
finCuerpo();
// **********************************************************


//vista
function cabecera() {}

//vista
function cuerpo() // Añadir aqui las variables
{
?>
    <br>
    <h3>Relación 8.</h3>
    <a href="../personalizar/index.php">Ir a Página Principal</a> | 
    <a href="../personalizar/personalizar.php">Ir a Personalizar</a> | 
    <a href="../texto/verTextos.php">Ir a Ver Textos</a>
<?php

}

// Aqui escribiremos las funciones

// FUNCIONES
