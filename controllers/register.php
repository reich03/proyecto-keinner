<?php

class Register extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->loadModel('register');
        error_log('register::construct->Inicio de main o vista principal');
    }

    function render()
    {
        error_log('register::render->Index de registro');
        $this->view->render('register/index');
    }

    function createAccount()
    {
        $email = $this->getPost('email');
        $confirm_email = $this->getPost('confirm_email');
        $password = $this->getPost('password');
        $confirm_password = $this->getPost('confirm_password');
        $first_name = $this->getPost('first_name');
        $last_name = $this->getPost('last_name');
        $phone = $this->getPost('phone');
        $user = $first_name;
        $role = 1;

        if ($email !== $confirm_email) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Los correos no coinciden"
                }).then(() => {
                    window.location.href = "' . constant('URL') . 'register";
                });
            </script>';
            return;
        }

        if ($password !== $confirm_password) {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Las contraseñas no coinciden"
                }).then(() => {
                    window.location.href = "' . constant('URL') . 'register";
                });
            </script>';
            return;
        }

        $registrationResult = $this->model->register($email, $password, $first_name, $last_name, $phone, $user, $role);
        if ($registrationResult === 'email_exists') {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "El correo electrónico ya está registrado"
                }).then(() => {
                    window.location.href = "' . constant('URL') . '/register";
                });
            </script>';
            return;
        }

        if ($registrationResult) {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Registro exitoso",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "' . constant('URL') . '";
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo registrar el usuario"
                }).then(() => {
                    window.location.href = "' . constant('URL') . 'register";
                });
            </script>';
        }
    }
}
