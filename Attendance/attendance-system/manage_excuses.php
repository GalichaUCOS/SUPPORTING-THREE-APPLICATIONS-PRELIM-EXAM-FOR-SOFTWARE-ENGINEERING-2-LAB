<?php
require_once __DIR__ . "/classes/Admin.php";
Admin::requireRole('admin');

$admin = new Admin();
$user = $_SESSION['user'];

$course_id = $_GET['course_id'] ?? null;
$excuses = $admin->viewExcusesByCourse($course_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $excuse_id = $_POST['excuse_id'] ?? null;
    if ($excuse_id && in_array($action, ['approve', 'reject'])) {
        if ($action === 'approve') {
            $admin->approveExcuse($excuse_id, $user['id']);
        } else {
            $admin->rejectExcuse($excuse_id, $user['id']);
        }
        header("Location: manage_excuses.php" . ($course_id ? "?course_id=$course_id" : ""));
        exit;
    }
}

$courses = $admin->getAllCourses();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Excuse Letters</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>Manage Excuse Letters</h1>
    <form method="get" class="mb-3">
        <label for="course_id" class="form-label">Filter by Course</label>
        <select id="course_id" name="course_id" class="form-select" onchange="this.form.submit()">
            <option value="">All Courses</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>" <?= ($course_id == $course['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($course['course_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Reason</th>
                <th>Submitted At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $excuses->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['reason'])) ?></td>
                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="excuse_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="post" style="display:inline-block;">
                        <input type="hidden" name="excuse_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                    <?php else: ?>
                        <em>No actions</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($excuses->num_rows === 0): ?>
            <tr><td colspan="6" class="text-center">No excuse letters found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
