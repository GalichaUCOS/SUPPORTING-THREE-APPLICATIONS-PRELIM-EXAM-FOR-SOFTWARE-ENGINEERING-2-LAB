<?php
require_once __DIR__ . "/User.php";

class Admin extends User {
    public function addCourse($name) {
        $stmt = $this->conn->prepare("INSERT INTO courses (course_name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function updateCourse($id, $name) {
        $stmt = $this->conn->prepare("UPDATE courses SET course_name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function deleteCourse($id) {
        // Will fail if referenced by users unless FK set to SET NULL (we used SET NULL)
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function viewAttendanceByCourseYear($course_id, $year_level) {
        $sql = "
          SELECT u.name, c.course_name, u.year_level, a.attended_at, a.status, a.is_late
          FROM attendance a
          JOIN users u ON a.student_id = u.id
          JOIN courses c ON u.course_id = c.id
          WHERE c.id = ? AND u.year_level = ?
          ORDER BY a.attended_at DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $course_id, $year_level);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function viewExcusesByCourse($course_id = null) {
        $sql = "
          SELECT e.id, u.name, c.course_name, e.reason, e.submitted_at, e.status
          FROM excuse_letters e
          JOIN users u ON e.student_id = u.id
          JOIN courses c ON u.course_id = c.id
        ";
        $params = [];
        $types = "";
        if ($course_id) {
            $sql .= " WHERE c.id = ?";
            $params[] = $course_id;
            $types .= "i";
        }
        $sql .= " ORDER BY e.submitted_at DESC";
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }

    public function approveExcuse($excuse_id, $admin_id) {
        $stmt = $this->conn->prepare("
            UPDATE excuse_letters
            SET status = 'approved', admin_id = ?, reviewed_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $admin_id, $excuse_id);
        return $stmt->execute();
    }

    public function rejectExcuse($excuse_id, $admin_id) {
        $stmt = $this->conn->prepare("
            UPDATE excuse_letters
            SET status = 'rejected', admin_id = ?, reviewed_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $admin_id, $excuse_id);
        return $stmt->execute();
    }
}
