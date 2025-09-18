<?php
require_once __DIR__ . "/classes/Student.php";
Student::requireRole('student');

$student = new Student();
$user = $_SESSION['user'];

$excuses = $student->viewExcuses($user['id']);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Excuse Letters</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>My Excuse Letters</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Reason</th>
                <th>Submitted At</th>
                <th>Status</th>
                <th>Reviewed At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $excuses->fetch_assoc()): ?>
            <tr>
                <td><?= nl2br(htmlspecialchars($row['reason'])) ?></td>
                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                <td>
                    <span class="badge bg-<?= $row['status'] === 'approved' ? 'success' : ($row['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                        <?= htmlspecialchars(ucfirst($row['status'])) ?>
                    </span>
                </td>
                <td><?= $row['reviewed_at'] ? htmlspecialchars($row['reviewed_at']) : 'N/A' ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($excuses->num_rows === 0): ?>
            <tr><td colspan="4" class="text-center">No excuse letters submitted yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
