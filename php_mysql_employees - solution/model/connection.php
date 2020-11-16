<?php
/**
 * Encapsulates a connection to the database 
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0 August 2020
 */
    class DB {
        /**
         * Opens a connection to the database
         * 
         * @returns a connection object
         */
        public function connect() {
            $server = 'localhost';
            $dbName = 'employees';
            $user = 'root';
            $pwd = '';

            $dsn = 'mysql:host=' . $server . ';dbname=' . $dbName . ';charset=utf8';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            try {
                $pdo = @new PDO($dsn, $user, $pwd, $options); 
            } catch (\PDOException $e) {
                echo 'Connection unsuccessful';
                die('Connection unsuccessful: ' . $e->getMessage());
                exit();
            }
            
            return($pdo);   
        }

        /**
         * Closes a connection to the database
         * 
         * @param the connection object to disconnect
         */
        public function disconnect($pdo) {
            $pdo = null;
        }
    }
?>