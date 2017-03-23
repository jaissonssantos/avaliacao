<?php


$response = new stdClass();

if (isset($_SESSION['ang_plataforma_uid']) && isset($_SESSION['ang_plataforma_email']) && isset($_SESSION['ang_plataforma_login'])) {
    $response->status = 'success';
    $response->message = 'Usuário autenticado';
    $response->session = array(
                            'uid' => $_SESSION['ang_plataforma_uid'],
                            'name' => $_SESSION['ang_plataforma_nome'],
                            'email' => $_SESSION['ang_plataforma_email'],
                            'establishment' => $_SESSION['ang_plataforma_estabelecimento'],
                            'profile' => $_SESSION['ang_plataforma_perfil'],
                            'plan' => $_SESSION['ang_plataforma_plano'],
                        );
} else {
    $response->status = 'error';
    $response->message = 'Usuário não autenticado';
}

echo json_encode($response);
