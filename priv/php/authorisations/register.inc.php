<?php
include_once 'priv/initialize.php';
include_once INCLUDES_PATH . 'db_connect.php';
include_once INCLUDES_PATH . 'psl-config.php';

$error_msg = "";

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //

    $prep_stmt = "SELECT id_usr FROM users_usr WHERE email_usr = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    // check existing email
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
            $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
        $stmt->close();
    }

    // check existing username
    $prep_stmt = "SELECT id_usr FROM users_usr WHERE username_usr = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // A user with this username already exists
            $error_msg .= '<p class="error">A user with this username already exists</p>';
            $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error line 55</p>';
        $stmt->close();
    }

    // TODO:
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.

    // check if member
    $prep_stmt = "SELECT id_mem FROM members_mem WHERE email_mem = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($idmem);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            // A user with this email exists
            $member = 2;
        } else {
            $member = 3;
        }
    } else {
        $error_msg .= '<p class="error">Database error line 79</p>';
        $stmt->close();
    }

    if (empty($error_msg)) {

        // Create hashed password using the password_hash function.
        // This function salts it with a random salt and can be verified with
        // the password_verify function.
        $password = password_hash($password, PASSWORD_BCRYPT);

        mysqli_set_charset($mysqli, "utf8");
        // Insert the new user into the database
        $sql = "INSERT INTO users_usr (username_usr, email_usr, password_usr,
                          idmem_usr) VALUES (?, ?, ?,?)";
        if ($insert_stmt = $mysqli->prepare($sql)) {
            $insert_stmt->bind_param('sssi', $username, $email, $password,
                $idmem);
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                header('Location: ../error.php?err=Registration failure: INSERT');
            } else {
                $last_id = $mysqli->insert_id;
            }
        }

        if ($insert_stmt = $mysqli->prepare("INSERT INTO user_role_uro (idusr_uro, idrol_uro) VALUES (?, ?)")) {
            $insert_stmt->bind_param('ii', $last_id, $member);
            // Execute the prepared query.
            if (!$insert_stmt->execute()) {
                header('Location: ../error.php?err=Registration failure: INSERT');
            }
        }

        header('Location: ./register_success.php');
    }
}
?>