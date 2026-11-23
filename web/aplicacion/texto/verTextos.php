<?php

include_once(dirname(__FILE__) . "/../../cabecera.php");

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

$arrayTextos = [];

if (isset($_POST["texto"])) {
    $textoNuevo = new RegistroTexto($_POST["texto"]);

    // $cadena = $textoNuevo->getCadena(); // Cadena escrita en el input
    // $fecha = $textoNuevo->getFechaHora()->format("d/m/Y"); // 23/11/2026
    // $hora = $textoNuevo->getFechaHora()->format("H:i:s"); // 14:44:38

    // $textoFormateado = $cadena . " - " . $fecha . " - " . $hora;

    $arrayTextos[] = $textoNuevo; // Guardar el objeto RegistroTexto
}

//dibuja la plantilla de la vista
inicioCabecera("TEXTOS");
cabecera();
finCabecera();
inicioCuerpo("TEXTOS", $barraUbi);
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
    <h3>Textos.</h3>
<?php

    // Para ello, definir un formulario con un text donde se indica el texto a registrar y 
    // un botón registrar. En esta página aparecerá también un textarea en el que se mostrarán
    // todos los textos indicados por el usuario junto con la fecha/hora en la que lo introdujo y
    // un botón Limpiar para borrar todos los textos.

    formulario();
}

// Aqui escribiremos las funciones

// FUNCIONES

function formulario(): void
{
?>
    <form action="" method="post">
        <textarea name="areaTextos" id="areaTextos" cols="52" rows="10" readonly></textarea>
        <br><br>
        <input type="text" name="texto" size="50"> <br>
        <button type="submit">Registrar texto</button>
        <button type="reset">Limpiar</button>
    </form>
<?php
}
