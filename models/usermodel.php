<?php

require_once 'core/model.php';

class UserModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($username, $password)
    {
        try {
            $query = $this->db->connect()->prepare('
                SELECT idcliente as id, nomb_cli as nombre, ape_cli as apellido, correo_cli as correo, clave_cli as clave, telefono,"user", idrol, \'cliente\' AS user_type 
                FROM cliente 
                WHERE correo_cli = :username OR nomb_cli = :username 
                UNION 
                SELECT idempleado as id, nomb_emple as nombre, ape_emple as apellido, correo_emple as correo, clave_emple as clave, telefono,"user", idrol, \'empleado\' AS user_type 
                FROM empleado 
                WHERE correo_emple = :username OR nomb_emple = :username
            ');

            $query->execute(['username' => $username]);

            if ($query->rowCount() > 0) {
                $user = $query->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['clave'])) {
                    error_log("model-user-login::login->Informacion exitosa, {$user['correo']}");
                    return $user;
                } else {
                    return null;
                }
            }

            return null;
        } catch (PDOException $e) {
            error_log('UserModel::login->PDOException ' . $e);
            return null;
        }
    }
}
