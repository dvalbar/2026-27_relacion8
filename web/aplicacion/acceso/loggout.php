<?php

// PÁGINA QUE CIERRA LA SESIÓN DEL USUARIO

// Abre la sesión y crea $acl y $acceso
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Quito el registro del usuario (deja _validado a false y lo guarda en la sesión)
$acceso->quitarRegistroUsuario();

// Vuelvo a la página de inicio
header("Location: /index.php");
exit;
