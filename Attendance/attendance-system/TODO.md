# Excuse Letter Module Implementation

## Steps to Complete

1. [x] Update Student.php: Add submitExcuse(reason) and viewExcuses() methods.
2. [x] Update Admin.php: Add viewExcusesByCourse(course_id), approveExcuse(excuse_id), rejectExcuse(excuse_id) methods.
3. [x] Create submit_excuse.php: Form for students to submit excuse letters.
4. [x] Create manage_excuses.php: Page for admins to view and manage excuses by course.
5. [x] Create view_excuses.php: Page for students to view their excuse statuses.
6. [x] Update dashboard.php: Add links for excuse-related pages based on role.

## Notes
- Database table `excuse_letters` assumed to be added separately with fields: id, student_id, reason, submitted_at, status, admin_id, reviewed_at.
- Update one file at a time as per user instruction.
