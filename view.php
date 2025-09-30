<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "airlinesdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$allowed_tables = [
    "aircrafts",
    "airports",
    "pilots",
    "crew_members",
    "flights",
    "passengers",
    "reservations"
];

$selectedTable = $_GET['table'] ?? '';
$tableData = [];
$columns = [];
$error = "";
$success = "";

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_row'], $_POST['table'], $_POST['pk'])) {
    $table = $_POST['table'];
    $pk = $_POST['pk'];
    $pk_value = $_POST['pk_value'];

    if (in_array($table, $allowed_tables)) {
        $stmt = $conn->prepare("DELETE FROM `$table` WHERE `$pk` = ?");
        $stmt->bind_param("s", $pk_value);
        if ($stmt->execute()) {
            $success = "Row deleted successfully.";
        } else {
            $error = "Error deleting row: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_row'], $_POST['table'], $_POST['pk'])) {
    $table = $_POST['table'];
    $pk = $_POST['pk'];
    $pk_value = $_POST['pk_value'];

    if (in_array($table, $allowed_tables)) {
        $updates = [];
        $types = "";
        $values = [];

        foreach ($_POST as $key => $val) {
            if (!in_array($key, ['update_row', 'table', 'pk', 'pk_value'])) {
                $updates[] = "`$key` = ?";
                $types .= "s";
                $values[] = $val;
            }
        }

        $types .= "s";
        $values[] = $pk_value;

        $sql = "UPDATE `$table` SET " . implode(', ', $updates) . " WHERE `$pk` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            $success = "Row updated successfully.";
        } else {
            $error = "Error updating row: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Load Table Data
if (!empty($selectedTable) && in_array($selectedTable, $allowed_tables)) {
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
} else if (!empty($selectedTable)) {
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
            <?php foreach ($allowed_tables as $table): ?>
                <?php $prettyName = ucwords(str_replace('_', ' ', $table)); ?>
                <option value="<?= $table ?>" <?= $table == $selectedTable ? "selected" : "" ?>><?= $prettyName ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Load Table</button>
    </form>

    <div class="table-display">
        <?php if (!empty($error)): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($success)): ?>
            <p class="success-msg"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!empty($tableData)): ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <th><?= htmlspecialchars($col->name) ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableData as $row): ?>
                        <tr>
                            <form method="POST" action="view.php?table=<?= htmlspecialchars($selectedTable) ?>">
                                <?php
                                $primaryKey = $columns[0]->name;
                                foreach ($columns as $col):
                                    $colName = $col->name;
                                ?>
                                    <td>
                                        <input type="text" name="<?= htmlspecialchars($colName) ?>" value="<?= htmlspecialchars($row[$colName]) ?>" style="width: 100%; border: none; background: transparent; color: #222;" />
                                    </td>
                                <?php endforeach; ?>
                                <td style="white-space: nowrap;">
                                    <input type="hidden" name="table" value="<?= htmlspecialchars($selectedTable) ?>">
                                    <input type="hidden" name="pk" value="<?= htmlspecialchars($primaryKey) ?>">
                                    <input type="hidden" name="pk_value" value="<?= htmlspecialchars($row[$primaryKey]) ?>">
                                    <button type="submit" name="update_row" style="margin-right: 6px;">Update</button>
                                    <button type="submit" name="delete_row" onclick="return confirm('Are you sure you want to delete this row?')">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($selectedTable !== ""): ?>
            <p>No data found in the selected table.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>
