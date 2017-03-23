<?php

if (session_id()) {
    session_destroy();
    $msg['status'] = 'success';
    $msg['message'] = 'logout do usuário realizado com sucesso';
    echo json_encode($msg);
} else {
    echo '{ "status": "error", "message": "not closed session user" }';
}
