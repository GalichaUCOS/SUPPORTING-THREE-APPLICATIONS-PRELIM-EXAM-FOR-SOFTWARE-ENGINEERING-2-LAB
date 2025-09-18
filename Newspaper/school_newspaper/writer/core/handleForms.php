<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

if (isset($_POST['insertArticleBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author_id = $_SESSION['user_id'];
    $image_url = '';
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = uniqid() . "_" . basename($_FILES["article_image"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["article_image"]["tmp_name"], $target_file)) {
            $image_url = "../uploads/" . $filename;
        }
    }

    if ($articleObj->createArticle($title, $description, $author_id, $image_url, $category_id)) {
        header("Location: ../index.php");
        exit;
    }
}

if (isset($_POST['editArticleBtn'])) {
	$title = $_POST['title'];
	$description = $_POST['description'];
	$article_id = $_POST['article_id'];
	$category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
	if ($articleObj->updateArticle($article_id, $title, $description, $category_id)) {
		header("Location: ../articles_submitted.php");
	}
}

if (isset($_POST['deleteArticleBtn'])) {
	$article_id = $_POST['article_id'];
	echo $articleObj->deleteArticle($article_id);
}

if (isset($_POST['requestEditBtn'])) {
    $article_id = intval($_POST['article_id']);
    $requester_id = $_SESSION['user_id'];

    // Get author_id for notification
    $article = $articleObj->getArticleById($article_id);
    $author_id = $article['author_id'];
    $title = $article['title'];

    // Insert edit request (status: pending)
    $articleObj->insertEditRequest($article_id, $requester_id);

    // Notify author
    $message = "Edit request for your article '<b>" . htmlspecialchars($title) . "</b>'";
    $userObj->insertNotification($author_id, $message);

    header("Location: ../index.php");
}

if (isset($_POST['acceptEditBtn'])) {
    $request_id = intval($_POST['request_id']);
    $articleObj->updateEditRequestStatus($request_id, 'accepted');
    // Notify requester
    $request = $articleObj->runQuerySingle("SELECT * FROM edit_requests WHERE request_id = ?", [$request_id]);
    $requester_id = $request['requester_id'];
    $message = "Your edit request has been accepted.";
    $userObj->insertNotification($requester_id, $message);
    header("Location: ../articles_submitted.php");
}

if (isset($_POST['rejectEditBtn'])) {
    $request_id = intval($_POST['request_id']);
    $articleObj->updateEditRequestStatus($request_id, 'rejected');
    // Notify requester
    $request = $articleObj->runQuerySingle("SELECT * FROM edit_requests WHERE request_id = ?", [$request_id]);
    $requester_id = $request['requester_id'];
    $message = "Your edit request has been rejected.";
    $userObj->insertNotification($requester_id, $message);
    header("Location: ../articles_submitted.php");
}
