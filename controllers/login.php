<?php

class Login extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->loadModel('user');
    }

    function authenticate()
    {
        $username = $this->getPost('mail');
        $password = $this->getPost('password');

        $user = $this->model->login($username, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            $redirectUrl = ($user['user_type'] == 'empleado') ? constant('URL') . '/' . 'dashboard' : constant('URL');
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Inicio de sesión exitoso",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "' . $redirectUrl . '";
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Nombre de usuario o contraseña incorrectos"
                }).then(() => {
                    window.location.href = "' . constant('URL') . '";
                });
            </script>';
        }
    }

    function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        echo '<script>
            Swal.fire({
                icon: "success",
                title: "Sesión cerrada",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = "' . constant('URL') . '";
            });
        </script>';
    }
}
