<?php

require_once __DIR__ . '/../database.php';

class LikelistModel {
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
        $query = "INSERT INTO USER_LIKE_BOOK (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function read($queryParams, $allowedKeys = [], $select = []) {
        // Filter the query parameters to only include allowed keys
        if (!empty($allowedKeys)) {
            $queryParams = array_intersect_key($queryParams, array_flip($allowedKeys));
        }
    
        // Create SELECT clause based on provided columns
        $selectClause = empty($select) ? '*' : implode(', ', $select);
    
        $conditions = [];
        foreach ($queryParams as $key => $value) {
            $conditions[] = "$key='$value'";
        }
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        $query = "SELECT $selectClause FROM USER_LIKE_BOOK $whereClause";
        $result = $this->connection->query($query);
        if ($result->num_rows > 0) {
            $likelists = [];
            while ($row = $result->fetch_assoc()) {
                $likelists[] = $row;
            }
            return $likelists;
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
        $query = "UPDATE USER_LIKE_BOOK SET " . implode(", ", $updates) . " WHERE id='$id'";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($user_id , $book_isbn) {
        $query = "DELETE FROM USER_LIKE_BOOK WHERE user_id='$user_id' AND book_isbn='$book_isbn'";
        if ($this->connection->query($query)) {
            return true;
        } else {
            return false;
        }
    }
    
}

?>
