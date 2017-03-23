<?php


$response = new stdClass();

if (isset($_SESSION['ang_plataforma_uid']) && isset($_SESSION['ang_plataforma_email']) && isset($_SESSION['ang_plataforma_login'])) {
    session_unset($_SESSION['ang_plataforma_uid']);
    session_unset($_SESSION['ang_plataforma_email']);
    session_unset($_SESSION['ang_plataforma_login']);

    $response->status = 'success';
    $response->message = 'sessão expirou!';
} else {
    $response->status = 'error';
    $response->message = 'ocorreu um erro ao encerrar a sessão!';
}

echo json_encode($response);
