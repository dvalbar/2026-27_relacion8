<?php

class RegistroTexto
{

    /**
     * Variables
     */
    private string $_cadena;
    private DateTime $_fechaHora;

    /**
     * Constructor
     *
     * @param string $cadena
     */
    public function __construct(string $cadena)
    {
        $this->_cadena = $cadena;
        $this->_fechaHora = new DateTime();
    }

    /**
     * Getter's
     */

    /**
     * Devuelve la cadena.
     *
     * @return string Devuelve la cadena
     */
    public function getCadena(): string
    {
        return $this->_cadena;
    }

    /**
     * Devuelve la fecha/hora
     *
     * @return DateTime Devuelve la fecha/hora
     */
    public function getFechaHora(): DateTime
    {
        return $this->_fechaHora;
    }

    // =========================================================================================
    // Todas las clases que se creen tienen deshabilitada la sobrecarga dinámica de propiedades.
    // =========================================================================================

    /**
     * Se ejecuta al escribir en una propiedad inaccesible o inexistente.
     * Lanza una excepción para evitar la sobrecarga de propiedades.
     *
     * @param string $name  Nombre de la propiedad.
     * @param mixed  $value Valor que se intentaba asignar.
     * @throws \Exception Siempre.
     * @return void No devuelve nada
     */
    public function __set(string $name, mixed $value): void
    {
        throw new \Exception("La propiedad $name no existe");
    }

    /**
     * Se ejecuta al leer una propiedad inaccesible o inexistente.
     * Lanza una excepción para evitar la sobrecarga de propiedades.
     *
     * @param string $name Nombre de la propiedad.
     * @return mixed
     * @throws \Exception Siempre.
     * @return mixed No devuelve nada
     */
    public function __get(string $name): mixed
    {
        throw new \Exception("La propiedad $name no existe");
    }

    /**
     * Se ejecuta al usar isset() o empty() sobre una propiedad inaccesible.
     * Siempre devuelve false porque no hay propiedades dinámicas.
     *
     * @param string $name Nombre de la propiedad.
     * @return bool Siempre devolverá falso
     */
    public function __isset(string $name): bool
    {
        // Ninguna propiedad inaccesible "existe"
        return false;
    }

    /**
     * Se ejecuta al usar unset() sobre una propiedad inaccesible.
     * No hace nada.
     *
     * @param string $name Nombre de la propiedad.
     * @return void No devuelve nada
     */
    public function __unset(string $name): void
    {
        ;
        // throw new \Exception('Not implemented');
    }
}
