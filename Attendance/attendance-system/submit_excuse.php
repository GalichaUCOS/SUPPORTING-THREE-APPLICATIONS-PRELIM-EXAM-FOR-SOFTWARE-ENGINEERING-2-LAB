<?php
require_once __DIR__ . "/classes/Student.php";
Student::requireRole('student');

$student = new Student();
$user = $_SESSION['user'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = trim($_POST['reason'] ?? '');
    if ($reason === '') {
        $message = "Please provide a reason for the excuse.";
    } else {
        $success = $student->submitExcuse($user['id'], $reason);
        if ($success) {
            $message = "Excuse letter submitted successfully.";
        } else {
            $message = "Failed to submit excuse letter. Please try again.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Submit Excuse Letter</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1>Submit Excuse Letter</h1>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post" action="submit_excuse.php">
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea id="reason" name="reason" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Excuse</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
