<?php

class database_manager {
    private $ADD_FILE = "INSERT INTO files (created_by, path, name, size, uploaded_on, last_changed, type) 
                         VALUES (?, ?, ?, ?, ?, ?, ?);";

    private $ADD_USER = "INSERT INTO accounts (email, username, password) 
                         VALUES (?, ?, ?);";

    private $ADD_SHARE = "INSERT INTO shares (shared_by, shared_to, file_name) 
                          VALUES (?, ?, ?);";

    private $GET_TYPE_ID = "SELECT id 
                            FROM types 
                            WHERE extension = ?;";

    private $GET_USER_BY_EMAIL = "SELECT id, email, username, password 
                         FROM accounts 
                         WHERE email = ?;";

    private $GET_USER_BY_USERNAME = "SELECT id, email, username, password 
                                  FROM accounts 
                                  WHERE username = ?;";

    private $GET_FILE = "SELECT files.id, created_by, path, name, size, uploaded_on, last_changed, type
    FROM files
    INNER JOIN types ON files.type = types.id 
    WHERE created_by = ? AND name = ?";

    private $GET_PATH = "SELECT path 
                         FROM files 
                         WHERE created_by = ? AND name = ?";

    private $GET_FILES_FOR_USER = "SELECT files.name, files.size, files.uploaded_on, files.last_changed, types.type_name 
                                   FROM files 
                                   INNER JOIN types ON files.type = types.id 
                                   WHERE created_by = ?;";

    private $GET_SHARE = "SELECT id, shared_by, shared_to, file_name
                          FROM shares 
                          WHERE shared_by = ? AND shared_to = ? AND file_name = ?;";

    private $GET_SHARES_WITH_USER = "SELECT accounts.username, shares.file_name, files.size, types.type_name, files.uploaded_on, files.last_changed 
                                     FROM shares 
                                     INNER JOIN files ON shares.shared_by = files.created_by AND shares.file_name = files.name
                                     INNER JOIN types ON files.type = types.id
                                     INNER JOIN accounts ON shared_by = accounts.id
                                     WHERE shared_to = ?;";

    private $GET_SHARES_BY_USER = "SELECT accounts.username, shares.file_name, files.size, types.type_name, files.uploaded_on, files.last_changed 
                                   FROM shares 
                                   INNER JOIN files ON shares.shared_by = files.created_by AND shares.file_name = files.name
                                   INNER JOIN types ON files.type = types.id
                                   INNER JOIN accounts ON shared_to = accounts.id
                                   WHERE shared_by = ?;";

    private $DELETE_FILE = "DELETE FROM files 
                            WHERE created_by = ? AND name = ?;";

    private $DELETE_SHARE = "DELETE FROM shares 
                             WHERE id = ?;";

    public function add_file($created_by, $path, $name, $size, $uploaded_on, $last_changed, $type) {
        return $this->execute_query($this->ADD_FILE, array($created_by, $path, $name, $size, $uploaded_on, $last_changed, $type));
    }

    public function add_user($email, $username, $password) {
        return $this->execute_query($this->ADD_USER, array($email, $username, $password));
    }

    public function add_share($shared_by, $shared_to, $file_name) {
        return $this->execute_query($this->ADD_SHARE, array($shared_by, $shared_to, $file_name));
    }

    public function get_type_id($extension) {
        return $this->select_query($this->GET_TYPE_ID, array($extension));
    }

    public function get_user_by_email($email) {
        return $this->select_query($this->GET_USER_BY_EMAIL, array($email));
    }

    public function get_user_by_username($email) {
        return $this->select_query($this->GET_USER_BY_USERNAME, array($email));
    }

    public function get_path($created_by, $name) {
        return $this->select_query($this->GET_PATH, array($created_by, $name));
    }

    public function get_file($created_by, $name) {
        return $this->select_query($this->GET_FILE, array($created_by, $name));
    }

    public function get_files_for_user($user_id) {
        return $this->select_query($this->GET_FILES_FOR_USER, array($user_id));
    }

    public function get_share($shared_by, $shared_to, $file_name) {
        return $this->select_query($this->GET_SHARE, array($shared_by, $shared_to, $file_name));
    }

    public function get_shares_with_user($shared_to) {
        return $this->select_query($this->GET_SHARES_WITH_USER, array($shared_to));
    }

    public function get_shares_by_user($shared_by) {
        return $this->select_query($this->GET_SHARES_BY_USER, array($shared_by));
    }

    public function delete_file_for_user($user_id, $file_name) {
        return $this->execute_query($this->DELETE_FILE, array($user_id, $file_name));
    }

    public function delete_share($share_id) {
        return $this->execute_query($this->DELETE_SHARE, array($share_id));
    }

    private function execute_query($query, $values) {
        $credentials = "mysql:host=localhost;dbname=webproject;charset=utf8";
        $user = "root";
        $password = "";
        $conn = new PDO($credentials, $user, $password);
        $stmt = $conn->prepare($query);
        return $stmt->execute($values);
    }

    private function select_query($query, $values) {
        $credentials = "mysql:host=localhost;dbname=webproject;charset=utf8";
        $user = "root";
        $password = "";
        $conn = new PDO($credentials, $user, $password);
        $stmt = $conn->prepare($query);
        if($stmt->execute($values)) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else return false;
    }
}

?>