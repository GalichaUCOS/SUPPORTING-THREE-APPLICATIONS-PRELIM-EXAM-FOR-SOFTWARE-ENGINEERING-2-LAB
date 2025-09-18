<?php
require_once __DIR__ . "/User.php";

class Student extends User {
    // Change this cutoff hour/minute for "Late"
    private $cutoffHour = 9;   // 9 AM
    private $cutoffMinute = 0; // 00

    public function markAttendance($student_id) {
        date_default_timezone_set('Asia/Manila'); // ensure local time
        $now = new DateTime();
        $cutoff = (new DateTime())->setTime($this->cutoffHour, $this->cutoffMinute, 0);

        $isLate = (int)($now > $cutoff);
        $status = $isLate ? "Late" : "Present";

        $stmt = $this->conn->prepare("
            INSERT INTO attendance (student_id, attended_at, status, is_late)
            VALUES (?, NOW(), ?, ?)
        ");
        $stmt->bind_param("isi", $student_id, $status, $isLate);
        return $stmt->execute();
    }

    public function viewHistory($student_id) {
        $stmt = $this->conn->prepare("
            SELECT attended_at, status, is_late
            FROM attendance
            WHERE student_id=?
            ORDER BY attended_at DESC
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function submitExcuse($student_id, $reason) {
        $stmt = $this->conn->prepare("
            INSERT INTO excuse_letters (student_id, reason, submitted_at, status)
            VALUES (?, ?, NOW(), 'pending')
        ");
        $stmt->bind_param("is", $student_id, $reason);
        return $stmt->execute();
    }

    public function viewExcuses($student_id) {
        $stmt = $this->conn->prepare("
            SELECT id, reason, submitted_at, status, reviewed_at
            FROM excuse_letters
            WHERE student_id=?
            ORDER BY submitted_at DESC
        ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
