<?php
$selectedDiagram = $_POST['diagram'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View Database Diagrams - Fly KUET</title>
    <link rel="stylesheet" href="dashboard.css" />
    <link rel="stylesheet" href="diagram.css" />
</head>
<body>

<?php include 'dashboard.php'; ?>

<main class="view-container">
    <form action="diagram.php" method="POST" class="view-form">
        <label for="diagram-select">Choose diagram to view:</label>
        <select name="diagram" id="diagram-select" required>
            <option value="" disabled <?= $selectedDiagram === '' ? 'selected' : '' ?>>Select a diagram</option>
            <option value="er" <?= $selectedDiagram === 'er' ? 'selected' : '' ?>>ER Diagram</option>
            <option value="schema" <?= $selectedDiagram === 'schema' ? 'selected' : '' ?>>Schema Diagram</option>
        </select>
        <button type="submit" name="load_diagram">Load Diagram</button>
    </form>

    <?php if (!empty($selectedDiagram)): ?>
        <div class="diagram-container">
            <?php if ($selectedDiagram === 'er'): ?>
                <h2>ER Diagram</h2>
                <img src="resources/ERDiagram.svg" alt="Entity Relationship Diagram" />
            <?php elseif ($selectedDiagram === 'schema'): ?>
                <h2>Schema Diagram</h2>
                <img src="resources/schema_diagram.svg" alt="Database Schema Diagram" />
            <?php else: ?>
                <p>Invalid selection.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
