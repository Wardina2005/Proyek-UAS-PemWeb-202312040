<?php
/**
 * Database Helper Class
 * Menyediakan fungsi-fungsi database yang aman dan mudah digunakan
 */

class Database {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Prepared statement untuk SELECT
     */
    public function select($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
    
    /**
     * Prepared statement untuk INSERT
     */
    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = str_repeat('?,', count($data) - 1) . '?';
        
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        
        $types = str_repeat('s', count($data));
        $stmt->bind_param($types, ...array_values($data));
        
        return $stmt->execute();
    }
    
    /**
     * Prepared statement untuk UPDATE
     */
    public function update($table, $data, $where, $where_params = []) {
        $set_clause = [];
        foreach (array_keys($data) as $column) {
            $set_clause[] = "$column = ?";
        }
        $set_clause = implode(',', $set_clause);
        
        $query = "UPDATE $table SET $set_clause WHERE $where";
        $stmt = $this->conn->prepare($query);
        
        $all_params = array_merge(array_values($data), $where_params);
        $types = str_repeat('s', count($all_params));
        $stmt->bind_param($types, ...$all_params);
        
        return $stmt->execute();
    }
    
    /**
     * Prepared statement untuk DELETE
     */
    public function delete($table, $where, $params = []) {
        $query = "DELETE FROM $table WHERE $where";
        $stmt = $this->conn->prepare($query);
        
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Get single row
     */
    public function get_row($query, $params = []) {
        $result = $this->select($query, $params);
        return $result->fetch_assoc();
    }
    
    /**
     * Get all rows
     */
    public function get_results($query, $params = []) {
        $result = $this->select($query, $params);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    /**
     * Get count
     */
public function get_count($table, $where = '1=1', $params = []) {
        try {
            $query = "SELECT COUNT(*) as count FROM $table WHERE $where";
            $result = $this->get_row($query, $params);
            return $result['count'];
        } catch (mysqli_sql_exception $e) {
            echo "SQL Error: " . $e->getMessage();
            return 0;
        }
    }
}
?>