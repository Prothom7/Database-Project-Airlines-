<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "airlinesdb";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$allowed_tables = [
    "aircrafts",
    "airports",
    "pilots",
    "crew_members",
    "flights",
    "passengers",
    "reservations"
];


$selectedTable = "";
$tableData = [];
$columns = [];

if (isset($_GET['table']) && in_array($_GET['table'], $allowed_tables)) {
    $selectedTable = $_GET['table'];

 
    $sql = "SELECT * FROM `$selectedTable`";
    $result = $conn->query($sql);

    if ($result) {
        $columns = $result->fetch_fields();

        while ($row = $result->fetch_assoc()) {
            $tableData[] = $row;
        }
    } else {
        $error = "Error retrieving data: " . $conn->error;
    }
} else if (isset($_GET['table'])) {
    $error = "Invalid table selected.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Tables - Fly KUET</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="view.css">
</head>
<body>

<?php include 'dashboard.php'; ?>

<main class="view-container">
    <form action="view.php" method="GET" class="view-form">
        <label for="table-select">Choose a table:</label>
        <select name="table" id="table-select" required>
            <option value="" disabled <?= $selectedTable == "" ? "selected" : "" ?>>Select a table</option>
            <?php
            foreach ($allowed_tables as $table) {
                $selected = ($table == $selectedTable) ? "selected" : "";
           
                $prettyName = ucwords(str_replace('_', ' ', $table));
                echo "<option value=\"$table\" $selected>$prettyName</option>";
            }
            ?>
        </select>
        <button type="submit">Load Table</button>
    </form>

    <div class="table-display">
        <?php if (!empty($error)) : ?>
            <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if (!empty($tableData)) : ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($columns as $col) : ?>
                            <th><?= htmlspecialchars($col->name) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableData as $row) : ?>
                        <tr>
                            <?php foreach ($columns as $col) : 
                                $colName = $col->name;
                                ?>
                                <td><?= htmlspecialchars($row[$colName]) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($selectedTable !== "") : ?>
            <p>No data found in the selected table.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
