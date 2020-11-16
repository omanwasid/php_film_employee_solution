<?php 
    define('NUM_ROWS', 25);

    include('view/header.php');
    
    if (isset($_GET['v']) && $_GET['v'] === 'e') {
        include('view/employee.php');
    } else {
        include('view/department.php');
    }
    
    include('view/footer.php'); 

?>