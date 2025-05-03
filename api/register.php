<?php
include("connect.php");

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address = $_POST['address'];
$age = $_POST['age'];
$role = $_POST['role'];
$image = $_FILES['photo']['name'];
$temp_name = $_FILES['photo']['tmp_name'];

// Validate input
if (empty($name) || empty($mobile) || empty($password) || empty($cpassword) || empty($address) || empty($age) || !in_array($role, [1, 2])) {
    echo '<script>
            alert("Please fill in all fields correctly.");
            window.location="../routes/register.html";
          </script>';
    exit();
}

if ($password !== $cpassword) {
    echo '<script>
            alert("Passwords do not match.");
            window.location="../routes/register.html";
          </script>';
    exit();
}

if ($age < 18) {
    echo '<script>
            alert("You must be at least 18 years old to register.");
            window.location="../routes/register.html";
          </script>';
    exit();
}

// File upload path
$targetDir = "../uploads/";
$fileName = basename($image);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

// Allow certain file formats
$allowTypes = ['jpg', 'png', 'jpeg', 'gif'];
if (in_array($fileType, $allowTypes)) {
    if (move_uploaded_file($temp_name, $targetFilePath)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $connect->prepare("INSERT INTO user (name, mobile, password, address, age, photo, role, status, votes) VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0)");
        $stmt->bind_param("ssssisi", $name, $mobile, $hashedPassword, $address, $age, $fileName, $role);

        if ($stmt->execute()) {
            echo '<script>
                    alert("Registration Successful!");
                    window.location="../";
                  </script>';
        } else {
            echo '<script>
                    alert("Error: Registration Failed!");
                    window.location="../routes/register.html";
                  </script>';
        }
        $stmt->close();
    } else {
        echo '<script>
                alert("Error: File Upload Failed!");
                window.location="../routes/register.html";
              </script>';
    }
} else {
    echo '<script>
            alert("Error: Invalid file type!");
            window.location="../routes/register.html";
          </script>';
}
?>
