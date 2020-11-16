<?php
    require_once('functions.php');

    debug($_POST);

    if (!isset($_POST['entity']) || !isset($_POST['action'])) {
        leave();
    } else {
        $entity = $_POST['entity'];
        $action = $_POST['action'];

        switch ($entity) {
            case 'person':
                require_once('person.php');
                $person = new Person;

                switch ($action) {
                    case 'search':
                        if (!isset($_POST['searchText'])) {
                            leave();
                        } else {
                            echo json_encode($person->search($_POST['searchText']));
                        }
                        break;
                    case 'add':
                        if (!isset($_POST['personName'])) {
                            leave();
                        } else {
                            echo json_encode($person->add($_POST['personName']));
                        }                        
                        break;
                    case 'delete':
                        if (!isset($_POST['personID'])) {
                            leave();
                        } else {
                            echo json_encode($person->delete($_POST['personID']));
                        }
                        break;                     
                }

                break;  
            case 'movie':
                require_once('movie.php');
                $movie = new Movie;

                switch ($action) {
                    case 'search':
                        if (!isset($_POST['searchText'])) {
                           leave();
                        } else {
                            echo json_encode($movie->search($_POST['searchText']));
                        }
                        break;
                    case 'get':
                        if (!isset($_POST['id'])) {
                            leave();
                        } else {
                            echo json_encode($movie->get($_POST['id']));
                        }
                        break;
                    case 'add':
                        if (!isset($_POST['info'])) {
                            leave();
                        } else {
                            echo json_encode($movie->add($_POST['info']));
                        }
                        break;
                    case 'update':
                        if (!isset($_POST['info'])) {
                            leave();
                        } else {
                            echo json_decode($movie->update($_POST['info']));
                        }
                        break;
                    case 'delete':
                        if (!isset($_POST['id'])) {
                            leave();
                        } else {
                            echo json_decode($movie->delete($_POST['id']));
                        }
                        break;
                }
                break;  
        }
    }
?>