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

$resultData = [];
$error = "";
$sqlQuery = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = $_POST['table'] ?? '';
    $columns = $_POST['columns'] ?? [];
    $conditions = $_POST['conditions'] ?? [];
    $group_by = $_POST['group_by'] ?? [];
    $having = $_POST['having'] ?? [];
    $order_by = $_POST['order_by'] ?? [];

    if (!in_array($table, $allowed_tables)) {
        $error = "Invalid table selected.";
    } else {
        $select_clause = empty(array_filter($columns)) ? "*" : implode(", ", array_filter($columns));
        $query = "SELECT $select_clause FROM `$table`";

        $where_clauses = array_filter($conditions);
        if (!empty($where_clauses)) {
            $query .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $group_clauses = array_filter($group_by);
        if (!empty($group_clauses)) {
            $query .= " GROUP BY " . implode(", ", $group_clauses);
        }

        $having_clauses = array_filter($having);
        if (!empty($having_clauses)) {
            $query .= " HAVING " . implode(" AND ", $having_clauses);
        }

        $order_clauses = array_filter($order_by);
        if (!empty($order_clauses)) {
            $query .= " ORDER BY " . implode(", ", $order_clauses);
        }

        $sqlQuery = $query;

        $res = $conn->query($query);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $resultData[] = $row;
            }
        } else {
            $error = "Query failed: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Custom Query - Fly KUET</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="customquery.css">
</head>
<body>

<?php include 'dashboard.php'; ?>

<main class="view-container">
    <h2 style="text-align:center;">Custom Query Builder</h2>

    <form method="POST" action="customquery.php" id="queryForm">

        <!-- Table Selection -->
        <div class="section">
            <label for="table">Select Table:</label>
            <select name="table" id="table" required>
                <option value="" disabled selected>Select table</option>
                <?php foreach ($allowed_tables as $tbl): ?>
                    <option value="<?= $tbl ?>" <?= (isset($_POST['table']) && $_POST['table'] === $tbl) ? "selected" : "" ?>>
                        <?= ucwords(str_replace('_', ' ', $tbl)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="section">
            <label>Select Columns:</label>
            <div id="select-columns">
                <div class="field-group">
                    <input type="text" name="columns[]" placeholder="e.g. id, name">
                </div>
            </div>
            <button type="button" class="add-btn" onclick="addField('select-columns', 'columns[]')">Add Column</button>
        </div>

        <div class="section">
            <label>Conditions (WHERE):</label>
            <div id="where-conditions">
                <div class="field-group">
                    <input type="text" name="conditions[]" placeholder="e.g. age > 30">
                </div>
            </div>
            <button type="button" class="add-btn" onclick="addField('where-conditions', 'conditions[]')">Add Condition</button>
        </div>

        <div class="section">
            <label>Group By:</label>
            <div id="group-by">
                <div class="field-group">
                    <input type="text" name="group_by[]" placeholder="e.g. department">
                </div>
            </div>
            <button type="button" class="add-btn" onclick="addField('group-by', 'group_by[]')">Add Group</button>
        </div>

        <div class="section">
            <label>Having Conditions:</label>
            <div id="having">
                <div class="field-group">
                    <input type="text" name="having[]" placeholder="e.g. COUNT(*) > 1">
                </div>
            </div>
            <button type="button" class="add-btn" onclick="addField('having', 'having[]')">Add Having</button>
        </div>

        <div class="section">
            <label>Order By:</label>
            <div id="order-by">
                <div class="field-group">
                    <input type="text" name="order_by[]" placeholder="e.g. name DESC">
                </div>
            </div>
            <button type="button" class="add-btn" onclick="addField('order-by', 'order_by[]')">Add Order</button>
        </div>

        <button type="submit">Run Query</button>
    </form>

    <?php if (!empty($sqlQuery)): ?>
        <h4 style="text-align:center; margin-top:30px;">Executed SQL:</h4>
        <pre><?= htmlspecialchars($sqlQuery) ?></pre>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($resultData)): ?>
        <div class="table-display">
            <table>
                <thead>
                    <tr>
                        <?php foreach (array_keys($resultData[0]) as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultData as $row): ?>
                        <tr>
                            <?php foreach ($row as $val): ?>
                                <td><?= htmlspecialchars($val) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)): ?>
        <p class="success-msg">Query ran successfully but returned no results.</p>
    <?php endif; ?>
</main>

<script>
    function addField(containerId, inputName) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.className = 'field-group';
        div.innerHTML = `<input type="text" name="${inputName}" placeholder="Enter value">`;
        container.appendChild(div);
    }
</script>

</body>
</html>
