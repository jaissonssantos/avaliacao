<?php


if (isset($_SESSION['ang_markday_uid']) && isset($_SESSION['ang_markday_email'])) {
    $msg['status'] = 'success';
    $msg['message'] = 'user authentified';
    $msg['ang_session_name'] = $_SESSION['ang_markday_name'];
    $msg['ang_session_thumbnail'] = $_SESSION['ang_markday_thumbnail'];
    echo json_encode($msg);
} else {
    echo '{ "status": "error", "message": "not authentified user" }';
}
