<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = htmlspecialchars(trim($_POST['contact_number']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
	$role = 'fiverr_administrator';

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password, $contact_number, $role)) {
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

if (isset($_POST['updateUserBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
		header("Location: ../profile.php");
	}
}

if (isset($_POST['insertOfferBtn'])) {
	$user_id = $_SESSION['user_id'];
	$proposal_id = $_POST['proposal_id'];
	$description = htmlspecialchars($_POST['description']);
	if ($offerObj->offerExists($user_id, $proposal_id)) {
		$_SESSION['message'] = "You have already submitted an offer to this proposal.";
		$_SESSION['status'] = '400';
		header("Location: ../index.php");
	} else {
		if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
			header("Location: ../index.php");
		}
	}
}

if (isset($_POST['updateOfferBtn'])) {
	$description = htmlspecialchars($_POST['description']);
	$offer_id = $_POST['offer_id'];
	if ($offerObj->updateOffer($description, $offer_id)) {
		$_SESSION['message'] = "Offer updated successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}

if (isset($_POST['deleteOfferBtn'])) {
	$offer_id = $_POST['offer_id'];
	if ($offerObj->deleteOffer($offer_id)) {
		$_SESSION['message'] = "Offer deleted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
	}
}

// Category Management
if (isset($_POST['insertCategoryBtn'])) {
	$category_name = htmlspecialchars(trim($_POST['category_name']));
	if (!empty($category_name)) {
		if ($categoryObj->createCategory($category_name)) {
			$_SESSION['message'] = "Category added successfully!";
			$_SESSION['status'] = '200';
			header("Location: ../manage_categories.php");
		} else {
			$_SESSION['message'] = "Error adding category!";
			$_SESSION['status'] = '400';
			header("Location: ../manage_categories.php");
		}
	} else {
		$_SESSION['message'] = "Category name cannot be empty!";
		$_SESSION['status'] = '400';
		header("Location: ../manage_categories.php");
	}
}

if (isset($_POST['updateCategoryBtn'])) {
	$category_id = $_POST['category_id'];
	$category_name = htmlspecialchars(trim($_POST['category_name']));
	if (!empty($category_name)) {
		if ($categoryObj->updateCategory($category_id, $category_name)) {
			$_SESSION['message'] = "Category updated successfully!";
			$_SESSION['status'] = '200';
			header("Location: ../manage_categories.php");
		} else {
			$_SESSION['message'] = "Error updating category!";
			$_SESSION['status'] = '400';
			header("Location: ../manage_categories.php");
		}
	} else {
		$_SESSION['message'] = "Category name cannot be empty!";
		$_SESSION['status'] = '400';
		header("Location: ../manage_categories.php");
	}
}

if (isset($_POST['deleteCategoryBtn'])) {
	$category_id = $_POST['category_id'];
	if ($categoryObj->deleteCategory($category_id)) {
		$_SESSION['message'] = "Category deleted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../manage_categories.php");
	} else {
		$_SESSION['message'] = "Error deleting category!";
		$_SESSION['status'] = '400';
		header("Location: ../manage_categories.php");
	}
}

// Subcategory Management
if (isset($_POST['insertSubcategoryBtn'])) {
	$category_id = $_POST['category_id'];
	$subcategory_name = htmlspecialchars(trim($_POST['subcategory_name']));
	if (!empty($subcategory_name) && !empty($category_id)) {
		if ($subcategoryObj->createSubcategory($category_id, $subcategory_name)) {
			$_SESSION['message'] = "Subcategory added successfully!";
			$_SESSION['status'] = '200';
			header("Location: ../manage_subcategories.php");
		} else {
			$_SESSION['message'] = "Error adding subcategory!";
			$_SESSION['status'] = '400';
			header("Location: ../manage_subcategories.php");
		}
	} else {
		$_SESSION['message'] = "All fields are required!";
		$_SESSION['status'] = '400';
		header("Location: ../manage_subcategories.php");
	}
}

if (isset($_POST['updateSubcategoryBtn'])) {
	$subcategory_id = $_POST['subcategory_id'];
	$subcategory_name = htmlspecialchars(trim($_POST['subcategory_name']));
	if (!empty($subcategory_name)) {
		if ($subcategoryObj->updateSubcategory($subcategory_id, $subcategory_name)) {
			$_SESSION['message'] = "Subcategory updated successfully!";
			$_SESSION['status'] = '200';
			header("Location: ../manage_subcategories.php");
		} else {
			$_SESSION['message'] = "Error updating subcategory!";
			$_SESSION['status'] = '400';
			header("Location: ../manage_subcategories.php");
		}
	} else {
		$_SESSION['message'] = "Subcategory name cannot be empty!";
		$_SESSION['status'] = '400';
		header("Location: ../manage_subcategories.php");
	}
}

if (isset($_POST['deleteSubcategoryBtn'])) {
	$subcategory_id = $_POST['subcategory_id'];
	if ($subcategoryObj->deleteSubcategory($subcategory_id)) {
		$_SESSION['message'] = "Subcategory deleted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../manage_subcategories.php");
	} else {
		$_SESSION['message'] = "Error deleting subcategory!";
		$_SESSION['status'] = '400';
		header("Location: ../manage_subcategories.php");
	}
}

