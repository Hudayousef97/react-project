<?php

require_once __DIR__ . '/../database.php';

class UserModel {
    private $connection;

    public function __construct() {
        $database = new Database();
        $this->connection = $database->connect();
    }

    public function __destruct() {
        $this->connection->close();
    }

    public function create($data , $allowedKeys = []) {
        if (!empty($allowedKeys)) {
            $data = array_intersect_key($data, array_flip($allowedKeys));
        }

        $keys = array_keys($data);
        $values = array_values($data);
        $query = "INSERT INTO USER (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function read($queryParams , $allowedKeys = [] , $select = []) {
        // Filter the query parameters to only include allowed keys
        if (!empty($allowedKeys)) {
            $queryParams = array_intersect_key($queryParams, array_flip($allowedKeys));
        }

        $selectClause = empty($select) ? '*' : implode(', ', $select);

        $conditions = [];
        foreach ($queryParams as $key => $value) {
            $conditions[] = "$key='$value'";
        }
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $query = "SELECT $selectClause FROM USER $whereClause";
        $result = $this->connection->query($query);
        if ($result->num_rows > 0) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            return null;
        }
    }

    public function update($id, $data, $allowedKeys = []) {
        // Filter the data to only include allowed keys
        if (!empty($allowedKeys)) {
            $data = array_intersect_key($data, array_flip($allowedKeys));
        }

        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key='$value'";
        }
        $query = "UPDATE USER SET " . implode(", ", $updates) . " WHERE id='$id'";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM USER WHERE id='$id'";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    public function checkCredential($username, $password) {
        $query = "SELECT * FROM USER WHERE username='$username'";
        $result = $this->connection->query($query);
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            return password_verify($password, $user['password']);
        } else {
            return false;
        }
    }
    
    public function setTokenExpiry($userId, $expiryTime = 5) {
        $query = "UPDATE USER SET token_expiry= DATE_ADD(NOW(), INTERVAL $expiryTime MINUTE) WHERE id='$userId'";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

}

?>
