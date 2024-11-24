<?php

require_once 'core/model.php';

class RegisterModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
    }

    public function isEmailRegistered($email, $table, $emailField)
    {
        try {
            $query = $this->db->connect()->prepare("SELECT COUNT(*) AS count FROM $table WHERE $emailField = :email");
            $query->execute(['email' => $email]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log('RegisterModel::isEmailRegistered->PDOException ' . $e);
            return false;
        }
    }

    public function register($email, $password, $first_name, $last_name, $phone, $user, $role)
    {
        try {
            $table = $role == 1 ? 'cliente' : 'empleado';
            $fields = [
                'correo' => $role == 1 ? 'correo_cli' : 'correo_emple',
                'clave' => $role == 1 ? 'clave_cli' : 'clave_emple',
                'nombre' => $role == 1 ? 'nomb_cli' : 'nomb_emple',
                'apellido' => $role == 1 ? 'ape_cli' : 'ape_emple',
                'telefono' => 'telefono',
                'user' => 'user',
                'rol' => 'idrol'
            ];

            if ($this->isEmailRegistered($email, $table, $fields['correo'])) {
                return 'email_exists';
            }

            $query = $this->db->connect()->prepare("
            INSERT INTO $table (
                {$fields['correo']}, 
                {$fields['clave']}, 
                {$fields['nombre']}, 
                {$fields['apellido']}, 
                {$fields['telefono']}, 
                \"{$fields['user']}\", 
                {$fields['rol']}
            ) VALUES (
                :email, 
                :password, 
                :first_name, 
                :last_name, 
                :phone, 
                :user, 
                :idrol
            )
        ");

            $query->execute([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'user' => $user,
                'idrol' => $role
            ]);

            return true;
        } catch (PDOException $e) {
            error_log('RegisterModel::register->PDOException ' . $e);
            return false;
        }
    }
}
