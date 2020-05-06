<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
<?php
// variable declaration
$username = "";
$email  = "";
$errors  = [];
// SIGN UP USER
if (isset($_POST['signup_btn'])) {
    // validate form values
    $errors = validateUser($_POST, ['signup_btn']);

    // receive all input values from the form. No need to escape... bind_param takes care of escaping
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt the password before saving in the database
    $profile_picture = uploadProfilePicture();
    $created_at = date('Y-m-d H:i:s');

    // if no errors, proceed with signup
    if (count($errors) === 0) {
        // insert user into database
        $query = "INSERT INTO users SET username=?, email=?, password=?, profile_picture=?, created_at=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $username, $email, $password, $profile_picture, $created_at);
        $result = $stmt->execute();
        if ($result) {
          $user_id = $stmt->insert_id;
            $stmt->close();
            loginById($user_id); // log user in
         } else {
             $_SESSION['error_msg'] = "Database error: Could not register user";
        }
     }
}
