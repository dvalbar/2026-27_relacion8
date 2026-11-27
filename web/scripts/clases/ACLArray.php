<?php

class ACLArray extends ACLBase
{
    /**
     * $_roles contiene los roles definidos en la ACL
     * Cada role sera un array asociativo de 
     * cod => [ "cod"=> ,
     *            "nombre" =>  ,
     *            "permisos" => [1=>false, 2=> XX, .........10=> XXX]
     *          ]
     * 
     */
    private $_roles = [];

    /**
     * $_usuarios contiene los usuarios definidos en la ACL
     * Cada usuario sera un array asociativo de
     * 
     * cod => [
     *          "cod" => ,
     *          "nick" =>  ,
     *          "nombre" => ,
     *          "contrasenia" =>  , usamos una función de cifrado.
     *          "cod_role" => ,
     *          "borrado" =>        
     * ]
     */
    private $_usuarios = [];

    /**
     * Constructor
     */
    function __construct()
    {
        // Añade los roles
        $this->anadirRole("normales", []);
        $this->anadirRole("administradores", [1 => true]);

        // Añade los usuario (Usuarios y contraseñas cambiados)
        $this->anadirUsuario(
            "Alumno",
            "alumno",
            "alumno",
            $this->getCodRole("normales")
        );
        $this->anadirUsuario(
            "Profesor",
            "profesor",
            "profesor",
            $this->getCodRole("administradores")
        );
        $this->anadirUsuario(
            "David Valverde",
            "david",
            "david",
            $this->getCodRole("administradores")
        );
        $this->anadirUsuario(
            "Vicente Tejero",
            "vicente",
            "vicente",
            $this->getCodRole("normales")
        );
    }

    /**
     * Añade un role a nuestra ACL
     * 
     * @param string $nombre Nombre del role a añadir 
     * @param array $permisos Permisos que tendrá el role. Array con hasta 10 permisos
     * @return bool True si se ha podido crear, false en caso contrario
     */
    function anadirRole(string $nombre, array $permisos = array()): bool
    {
        // Pongo el nombre en minuscula
        $nombre = mb_strtolower($nombre);

        // Inicializo un arracon todos los permisos a false
        $per = [
            1 => false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false,
            false
        ];

        // Compruebo si ya existe el role
        if ($this->getCodRole($nombre) !== false)
            return false;

        // Reviso los permisos
        foreach ($per as $clave => $val) {
            if (isset($permisos[$clave])) {
                $per[$clave] = boolval($permisos[$clave]);
            }
        }

        // Busco cual es la ultima clave
        $cont = 0;

        foreach ($this->_roles as $clave => $role) {
            if ($role["cod"] > $cont)
                $cont = $role["cod"];
        }

        $cont++;
        // Creo el role
        $this->_roles[$cont] = [
            "cod" => $cont,
            "nombre" => $nombre,
            "permisos" => $per
        ];

        return true;
    }

    /**
     * Función que localiza un role y devuelve el código de ese role o false si no
     * lo encuentra
     *
     * @param string $nombre nombre del role
     * @return integer|false Devuelve el código de role para el nombre indicado o false si no encuentra el role
     */
    function getCodRole(string $nombre): int|false
    {
        // Pongo el nombre en minuscula
        $nombre = mb_strtolower($nombre);

        foreach ($this->_roles as $clave => $role) {
            if ($role["nombre"] == $nombre)
                return $role["cod"];
        }

        return false;
    }

    /**
     * Función que comprueba la existencia de un role
     *
     * @param integer $codRole role a buscar
     * @return boolean Devuelve true si lo encuentra o false en caso contrario 
     */
    function existeRole(int $codRole): bool
    {
        return isset($this->_roles[$codRole]);
    }

    /**
     * Función que devuelve los permisos de un role dado
     *
     * @param integer $codRole Role a buscar
     * @return array|false Devuelve los permisos o false si no encuentra el role
     */
    function getPermisosRole(int $codRole): array|false
    {
        // Compruebo que existe el codigo de role
        if (!$this->existeRole($codRole))
            return false;

        return ($this->_roles[$codRole]["permisos"]);
    }

    /**
     * Función que devuelve si un role tiene o no un permiso concreto
     *
     * @param integer $codRole Role a buscar
     * @param integer $numero Número de permiso
     * @return boolean True si encuentra el role y lo tiene. False en cualquier otro caso
     */
    function getPermisoRole(int $codRole, int $numero): bool
    {
        if (!$this->existeRole($codRole))
            return false;

        $permisos = $this->getPermisosRole($codRole);

        return isset($permisos[$numero]) && $permisos[$numero];
    }

    /**
     * Función que añade un nuevo usuario a nuestra ACL
     *
     * @param string $nombre Nombre del usuario
     * @param string $nick Nick unico para el usuario
     * @param string $contrasenia contraseña del usuario
     * @param integer $codRole Role a asignarle
     * @return boolean Devuelve true si puede crearlo. False en caso contrario
     */
    function anadirUsuario(string $nombre, string $nick, string $contrasenia, int $codRole): bool
    {
        // Pongo el nick as minuscula
        $nick = mb_strtolower($nick);

        // El nick debe ser único compruebo que no 
        // exista ya
        if ($this->existeUsuario($nick))
            return false;

        // Compruebo que exista el role
        if (!$this->existeRole($codRole))
            return false;

        // La contraseña la guardo encriptada
        $contrasenia = password_hash($contrasenia, PASSWORD_BCRYPT);

        // Busco cual es el ultimo codigo
        $cont = 0;

        foreach ($this->_usuarios as $clave => $usu) {
            if ($usu["cod"] > $cont)
                $cont = $usu["cod"];
        }

        $cont++;

        // Añado el usuario
        $this->_usuarios[$cont] = [
            "cod" => $cont,
            "nick" =>  $nick,
            "nombre" => $nombre,
            "contrasenia" =>  $contrasenia,
            "cod_role" => $codRole,
            "borrado" => false
        ];

        return true;
    }


    /**
     * Obtiene el código de usuario para un nick dado
     *
     * @param string $nick nick del usuario a buscar
     * @return integer|false Devuelve el codigo del usuario o false si no lo encuentra
     */
    function getCodUsuario(string $nick): int|false
    {
        // Pongo el nick n minuscula
        $nick = mb_strtolower($nick);

        foreach ($this->_usuarios as $clave => $usu) {
            if ($usu["nick"] == $nick) {   // Se encuentra el usuario
                return $clave;
            }
        }

        // No se encuentra
        return false;
    }

    /**
     * Verifica si existe un usuario dado un código
     *
     * @param integer $codUsuario Código del usuario a verificar
     * @return boolean Devuelve si existe o no el usuario
     */
    function existeCodUsuario(int $codUsuario): bool
    {
        return isset($this->_usuarios[$codUsuario]);
    }

    /**
     * Verifica si existe o no un usuario con el nick dado
     *
     * @param string $nick Nick del usuario a comprobar
     * @return boolean Devuelve true si encuentra el usuario y 
     * false en caso contrario
     */
    function existeUsuario(string $nick): bool
    {
        $encontrado = false;
        foreach ($this->_usuarios as $indice => $usuario) {
            if ($usuario["nick"] == strtolower($nick)) {
                $encontrado = true;
            }
        }
        return $encontrado;
    }

    /**
     * Función que comprueba que existe un usuario y la contraseña indicada es la correcta
     *
     * @param string $nick Nick del usuario a comprobar
     * @param string $contrasenia Contraseña del usuario a comprobar
     * @return boolean Devuelve true si existe el usuario y tiene la contraseña indicada. 
     * False en otro caso
     */
    function esValido(string $nick, string $contrasenia): bool
    {
        // Pongo el nick n minuscula
        $nick = mb_strtolower($nick);

        // Compruebo si existe el nick
        if (!$this->existeUsuario($nick))
            return false;

        // Recojo cual es el codigo
        $codigo = $this->getCodUsuario($nick);

        // Compruebo la contraseña
        if (!password_verify($contrasenia, $this->_usuarios[$codigo]["contrasenia"]))
            return false;

        // Es valido
        return true;
    }

    /**
     * Función que comprueba si un usuario tiene un permiso concreto
     *
     * @param integer $codUsuario Usuario a buscar
     * @param integer $numero Permiso a buscar
     * @return boolean Devuelve true si existe el usuario y tiene el permiso. 
     * False en otro caso
     */
    function getPermiso(int $codUsuario, int $numero): bool
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        $codRole = $this->_usuarios[$codUsuario]["cod_role"];

        return $this->getPermisoRole($codRole, $numero);
    }

    /**
     * Función que devuelve los permisos de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @return array|false Devuelve los permisos del usuario o false si 
     * no existe el usuario
     */
    function getPermisos(int $codUsuario): array|false
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        $codRole = $this->_usuarios[$codUsuario]["cod_role"];

        return $this->getPermisosRole($codRole);
    }

    /**
     * Función que devuelve el nombre de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @return string|false Devuelve el nombre del usuario o false si no existe
     */
    function getNombre(int $codUsuario): string|false
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        return $this->_usuarios[$codUsuario]["nombre"];
    }

    /**
     * Devuelve si un usuario está borrado
     *
     * @param integer $codUsuario Usuario a buscar.
     * @return boolean true si el usuario existe y no está borrado.
     * False en otro caso
     */
    function getBorrado(int $codUsuario): bool
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        return !$this->_usuarios[$codUsuario]["borrado"];
    }

    /**
     * Devuelve el role que tiene un usuario concreto
     *
     * @param integer $codUsuario Usuario a buscar
     * @return integer|false Devuelve el role del usuario o false si no existe.
     */
    function getUsuarioRole(int $codUsuario): int|false
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        return $this->_usuarios[$codUsuario]["cod_role"];
    }

    /**
     * Función que asigna un nombre a un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @param string $nombre Nombre a asignar
     * @return boolean Devuelve true si ha podido asignar el nombre, false en otro caso
     */
    function setNombre(int $codUsuario, string $nombre): bool
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        $this->_usuarios[$codUsuario]["nombre"] = $nombre;

        return true;
    }

    /**
     * Función que asigna una contraseña a un usuario
     *
     * @param integer $codUsuario usuario a buscar
     * @param string $contrasenia contraseña a asignar
     * @return boolean Devuelve true si ha podido asignar la contraseña
     * False en otro caso
     */
    function setContrasenia(int $codUsuario, string $contrasenia): bool
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        $this->_usuarios[$codUsuario]["contrasenia"] = password_hash($contrasenia, PASSWORD_BCRYPT);

        return true;
    }

    /**
     * Función que borra/desborra lógicamente un usuario 
     *
     * @param integer $codUsuario Usuario a buscar
     * @param boolean $borrado Estado a asignar
     * @return boolean Devuelve true si ha podido asignar el estado. 
     * False en otro caso
     */
    function setBorrado(int $codUsuario, bool $borrado): bool
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;


        $this->_usuarios[$codUsuario]["borrado"] = $borrado;

        return true;
    }

    /**
     * Función que cambia el role de un usuario
     *
     * @param integer $codUsuario Usuario a buscar
     * @param integer $role Role a asignar
     * @return boolean Devuelve true si ha podido asignar el role al usuario.
     * False si no existe el usuario, role o no ha podido asignarlo
     */
    function setUsuarioRole(int $codUsuario, int $role): bool
    {
        if (!$this->existeCodUsuario($codUsuario))
            return false;

        if (!$this->existeRole($role))
            return false;

        $this->_usuarios[$codUsuario]["cod_role"] = $role;

        return true;
    }

    /**
     * Devuelve un array con todos los usuarios existentes. 
     * La clave es el codigo de usuario, el valor es el nick del usuario 
     *
     * @return array Array con todos los usuarios existentes
     */
    function dameUsuarios(): array
    {
        $usuarios = [];

        foreach ($this->_usuarios as $clave => $usuario)
            $usuarios[$clave] = $usuario["nick"];

        return $usuarios;
    }

    /**
     * Devuelve un array con todos los roles existentes. 
     * La clave es el codigo de role, el valor es el nombre del role 
     *
     * @return array Array con todos los roles existentes
     */
    function dameRoles(): array
    {
        $roles = [];

        foreach ($this->_roles as $clave => $role)
            $roles[$clave] = $role["nombre"];

        return $roles;
    }
}
