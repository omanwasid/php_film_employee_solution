        <h4>Departments</h4>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th><a href="index.php?v=d&s=name" class="header">Name</a></th>
                    <th><a href="index.php?v=d&s=manager" class="header">Manager</a></th>
                </tr>
            </thead>
            <tbody>
            <?php
                require_once 'model/department.php';

                if (isset($_GET['s'])) {
                    $sort = $_GET['s'];
                } else {
                    $sort = '';
                }

                $departments = new Department();
                foreach ($departments->list($sort) as $department) {
            ?>
                <tr>
                    <td><?=$department['dept_name'] ?></td>
                    <td><?=$department['manager'] ?></td>
                </tr>        
            <?php } ?>
            </tbody>
        </table>