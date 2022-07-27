<?php

    if (strlen(session_id()) === 0) {
        session_start();
        if ($_SESSION['progress'] == 100) {
            unset($_SESSION['progress']);
        }
    }

    if (isset($_SESSION['progress'])) {
        echo $_SESSION['progress'];
        if ($_SESSION['progress'] == 100) {
            unset($_SESSION['progress']);
        }
    } 
    else {
        echo '0';
    }
?>
