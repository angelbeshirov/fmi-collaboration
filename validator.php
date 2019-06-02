<?php

    function validateRegistration($newUser) {
        shouldStartSession();
        $errors = [];
        $GET_USER = "SELECT * FROM accounts WHERE email=?;";
        $result = selectQuery($GET_USER, array($newUser["email"]));
        if($result) {
            $errors["error_description"] = "Вече съществува потребител с този имейл.";
        }

        return $errors;
    }
?>