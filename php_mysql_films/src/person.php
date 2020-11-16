<?php
/**
 * Person class
 * 
 * @author Arturo Mora-Rioja
 * @version 1.0 August 2020:
 */
require_once("connection.php");

    class Person extends DB {
        /**
         * Retrieves the persons whose name includes a certain text
         * 
         * @param   text upon which to execute the search
         * @return  an array with person information
         */
        function search($searchText) {
            $query = <<<'SQL'
                SELECT person_id, person_name
                FROM person
                WHERE person_name LIKE ?
                ORDER BY person_name;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['%' . $searchText . '%']);                

            $this->disconnect();

            return $stmt->fetchAll();                
        }

        /**
         * Inserts a new person
         * 
         * @param   name of the new person
         * @return  the ID of the new person, or -1 if the person already exists
         */
        function add($name) {

            // Check if the person already exists
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM person WHERE person_name = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);            
            if ($stmt->fetch()['total'] > 0) {
                return -1;
            }

            // Insert the person
            $query = <<<'SQL'
                INSERT INTO person (person_name) VALUES (?);
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);

            $newID = $this->pdo->lastInsertId();
            $this->disconnect();

            return $newID;
        }

        /**
         * Deletes a person
         * 
         * @param   ID of the person to delete
         * @return  true if the deletion was successful, or -1 if the person is associated to any movie
         */
        function delete($id) {

            // Check if the person is associated to any movie
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM movie_director WHERE person_id = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);
            if ($stmt->fetch()['total'] > 0) {
                return -1;
            }
            $query = <<<'SQL'
                SELECT COUNT(*) AS total FROM movie_cast WHERE person_id = ?;
            SQL;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);
            if ($stmt->fetch()['total'] > 0) {
                return -1;
            }

            // Delete the person
            $query = <<<'SQL'
                DELETE FROM person WHERE person_id = ?;
            SQL;

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$id]);

            $this->disconnect();

            return true;
        }
    }
?>