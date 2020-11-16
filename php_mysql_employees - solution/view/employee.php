        <h4>Employees</h4>
    </header>
    <main>
        <?php
        if (isset($_GET['search'])) {
            $searchText = $_GET['search'];
            $urlSearch = "&search=$searchText";
        } else {
            $searchText = '';
            $urlSearch = '';
        }
        ?>
        <form action="index.php" method="GET">
            <fieldset>
                <label for="txtSearch">Name</label>
                <input type="text" id="txtSearch" name="search" value="<?=$searchText ?>" required>
                <input type="hidden" name="v" value="e">
                <button type="submit">Search</button>
            </fieldset>
        </form>
        <table>
            <thead>
                <tr>
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=last_name" class="header">Last Name</a></th>
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=first_name" class="header">First Name</a></th>                    
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=department" class="header">Department</a></th>                    
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=gender" class="header">Gender</a></th>                    
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=birth_date" class="header">Birth Date</a></th>                    
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=hire_date" class="header">Hire Date</a></th>                    
                    <th><a href="index.php?v=e<?=$urlSearch ?>&s=salary" class="header number">Salary</a></th>                    
                </tr>
            </thead>
            <tbody>
            <?php
                require_once 'model/employee.php';

                if (isset($_GET['r'])) {
                    $range = (int) htmlspecialchars($_GET['r']);
                } else {
                    $range = 1;
                }

                $total = 0;

                if (isset($_GET['s'])) {
                    $sort = $_GET['s'];
                } else {
                    $sort = '';
                }

                $employees = new Employee();
                if (isset($_GET['search'])) {
                    $employeeList = $employees->search($searchText, $range, $sort);
                } else {
                    $employeeList = $employees->list($range, $sort);
                }
                foreach ($employeeList as $employee) {
                    $total++;
            ?>
                <tr>
                    <td><?=htmlspecialchars($employee['last_name']) ?></td>
                    <td><?=htmlspecialchars($employee['first_name']) ?></td>
                    <td><?=htmlspecialchars($employee['dept_name']) ?></td>
                    <td><?=htmlspecialchars($employee['gender']) ?></td>
                    <td><?=htmlspecialchars($employee['birth_date']) ?></td>
                    <td><?=htmlspecialchars($employee['hire_date']) ?></td>
                    <td class="number"><?=$employee['salary'] ?></td>
                </tr>        
            <?php 
                } 
                $firstEmployee = (($range - 1) * NUM_ROWS) + 1;
                $lastEmployee = $firstEmployee + $total - 1;
            ?>
            </tbody>
        </table>
        <section>
            <!-- Data navigation -->
            <?php if ($range > 1) { ?>
                <a href="index.php?v=e&r=<?=($range - 1) ?><?=(isset($_GET['search']) ? "&search=$searchText" : '') ?><?=(isset($_GET['s']) ? "&s=$sort" : '') ?>">&lt;&lt;</a>
            <?php } ?>
            &nbsp;<?=$range ?>&nbsp;
            <?php if ($total === NUM_ROWS) { ?>
                <a href="index.php?v=e&r=<?=($range + 1) ?><?=(isset($_GET['search']) ? "&search=$searchText" : '') ?><?=(isset($_GET['s']) ? "&s=$sort" : '') ?>">&gt;&gt;</a>
            <?php } ?>
            <?php if ($lastEmployee === 0) { ?>
                &nbsp;No employees to show
            <?php } else { ?>
                &nbsp;Showing employees <?=$firstEmployee ?> to <?=$lastEmployee ?>
            <?php } ?>
        </section> 