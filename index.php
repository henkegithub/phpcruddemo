<?php
// Verbindung zur SQLite-Datenbank herstellen
$pdo = new PDO('sqlite:tasks.sqlite');

// Funktion zum Erstellen einer Aufgabe
function createTask($title, $description) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO tasks (title, description) VALUES (?, ?)');
    return $stmt->execute([$title, $description]);
}

// Funktion zum Abrufen aller Aufgaben
function getTasks() {
    global $pdo;
    $stmt = $pdo->query('SELECT * FROM tasks');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Funktion zum Aktualisieren einer Aufgabe
function updateTask($id, $title, $description) {
    global $pdo;
    $stmt = $pdo->prepare('UPDATE tasks SET title = ?, description = ? WHERE id = ?');
    return $stmt->execute([$title, $description, $id]);
}

// Funktion zum Löschen einer Aufgabe
function deleteTask($id) {
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ?');
    return $stmt->execute([$id]);
}

// Überprüfen, ob Formulardaten gesendet wurden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        createTask($_POST['title'], $_POST['description']);
    } elseif (isset($_POST['update'])) {
        updateTask($_POST['id'], $_POST['title'], $_POST['description']);
    } elseif (isset($_POST['delete'])) {
        deleteTask($_POST['id']);
    }
}

// Aufgaben abrufen
$tasks = getTasks();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Aufgabenliste</title>
</head>
<body>
    <h1>Aufgabenliste</h1>

    <h2>Neue Aufgabe erstellen</h2>
    <form method="post">
        <label for="title">Titel:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="description">Beschreibung:</label>
        <textarea id="description" name="description"></textarea>
        <br>
        <button type="submit" name="create">Erstellen</button>
    </form>

    <h2>Aufgaben</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <strong><?php echo htmlspecialchars($task['title']); ?></strong>
                <p><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                    <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>">
                    <textarea name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    <button type="submit" name="update">Aktualisieren</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                    <button type="submit" name="delete">Löschen</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
