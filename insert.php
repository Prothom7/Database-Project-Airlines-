<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airlinesdb";

// Connect to DB
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

$foreign_keys = [
    "pilots" => [
        "assigned_aircraft" => ["aircrafts", "aircraft_id"]
    ],
    "crew_members" => [
        "assigned_aircraft" => ["aircrafts", "aircraft_id"]
    ],
    "flights" => [
        "aircraft_id" => ["aircrafts", "aircraft_id"],
        "departure_airport" => ["airports", "airport_code"],
        "arrival_airport" => ["airports", "airport_code"]
    ],
    "reservations" => [
        "passenger_id" => ["passengers", "passengers_id"],
        "flight_number" => ["flights", "flight_number"]
    ]
];

$selectedTable = $_POST['table'] ?? '';
$success = '';
$error = '';
$columns = [];
$foreignOptions = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Fetch foreign key options
    if (isset($foreign_keys[$selectedTable])) {
        foreach ($foreign_keys[$selectedTable] as $fkColumn => [$refTable, $refColumn]) {
            $res = $conn->query("SELECT `$refColumn` FROM `$refTable`");
            $foreignOptions[$fkColumn] = [];
            while ($row = $res->fetch_assoc()) {
                $foreignOptions[$fkColumn][] = $row[$refColumn];
            }
        }
    }

    // Handle Insert
    if (isset($_POST['insert_data']) && in_array($selectedTable, $allowed_tables)) {
        $result = $conn->query("SHOW COLUMNS FROM `$selectedTable`");
        while ($col = $result->fetch_assoc()) {
            $columns[] = $col;
        }

        $values = [];
        foreach ($columns as $col) {
            $colName = $col['Field'];
            $val = $_POST[$colName] ?? 'NULL';
            $val = $val === '' ? 'NULL' : "'" . $conn->real_escape_string($val) . "'";
            $values[] = $val;
        }

        $colNames = implode(", ", array_column($columns, 'Field'));
        $valString = implode(", ", $values);

        $sql = "INSERT INTO `$selectedTable` ($colNames) VALUES ($valString)";
        if ($conn->query($sql)) {
            $success = "Data inserted successfully into '$selectedTable'";
        } else {
            $error = "Insert failed: " . $conn->error;
        }
    }

    // Show form after "Insert into Table" clicked
    if (isset($_POST['load_table']) && in_array($selectedTable, $allowed_tables)) {
        $result = $conn->query("SHOW COLUMNS FROM `$selectedTable`");
        while ($col = $result->fetch_assoc()) {
            $columns[] = $col;
        }

        // Load foreign key options
        if (isset($foreign_keys[$selectedTable])) {
            foreach ($foreign_keys[$selectedTable] as $fkColumn => [$refTable, $refColumn]) {
                $res = $conn->query("SELECT `$refColumn` FROM `$refTable`");
                $foreignOptions[$fkColumn] = [];
                while ($row = $res->fetch_assoc()) {
                    $foreignOptions[$fkColumn][] = $row[$refColumn];
                }
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert into Table - Fly KUET</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="insert.css">
</head>
<body>

<?php include 'dashboard.php'; ?>

<main class="view-container">
    <form action="insert.php" method="POST" class="view-form">
        <label for="table-select">Choose a table:</label>
        <select name="table" id="table-select" required>
            <option value="" disabled <?= $selectedTable == "" ? "selected" : "" ?>>Select a table</option>
            <?php foreach ($allowed_tables as $table): ?>
                <option value="<?= $table ?>" <?= $table == $selectedTable ? "selected" : "" ?>>
                    <?= ucwords(str_replace('_', ' ', $table)) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="load_table">Insert into Table</button>
    </form>

    <?php if (!empty($success)): ?>
        <p class="success-msg"><?= htmlspecialchars($success) ?></p>
    <?php elseif (!empty($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($columns) && isset($_POST['load_table'])): ?>
        <form action="insert.php" method="POST" class="insert-form">
            <input type="hidden" name="table" value="<?= htmlspecialchars($selectedTable) ?>">
            <?php foreach ($columns as $col): ?>
                <div class="form-group">
                    <label><?= $col['Field'] ?><?= $col['Null'] === 'NO' ? ' *' : '' ?></label>
                    <?php if (isset($foreignOptions[$col['Field']])): ?>
                        <select name="<?= $col['Field'] ?>" <?= $col['Null'] === 'NO' ? 'required' : '' ?>>
                            <option value="" disabled selected>Select <?= $col['Field'] ?></option>
                            <?php foreach ($foreignOptions[$col['Field']] as $opt): ?>
                                <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input 
                            type="text" 
                            name="<?= $col['Field'] ?>" 
                            placeholder="Enter <?= $col['Field'] ?>"
                            <?= $col['Null'] === 'NO' ? 'required' : '' ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" name="insert_data">Submit Data</button>
        </form>
    <?php endif; ?>
</main>

</body>
</html>
