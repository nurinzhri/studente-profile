<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'uitm_eprofile');

if ($_SESSION['role'] !== 'admin') {
    header('Location: list_profiles.php');
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM profiles WHERE id = $id");
$profile = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $address = $_POST['address'];
    $faculty = $_POST['faculty'];

    // Handle profile picture update
    if (!empty($_FILES['profile_picture']['name'])) {
        $profilePicture = time() . '_' . $_FILES['profile_picture']['name'];
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/" . $profilePicture);
    } else {
        $profilePicture = $profile['profile_picture'];
    }

    $stmt = $conn->prepare("UPDATE profiles SET profile_picture = ?, name = ?, email = ?, phone_number = ?, address = ?, faculty = ? WHERE id = ?");
    $stmt->bind_param('ssssssi', $profilePicture, $name, $email, $phone, $address, $faculty, $id);

    if ($stmt->execute()) {
        header('Location: list_profiles.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $profile['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $profile['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $profile['phone_number']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" required><?php echo $profile['address']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="faculty" class="form-label">Faculty</label>
                <input type="text" class="form-control" id="faculty" name="faculty" value="<?php echo $profile['faculty']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
        <a href="list_profiles.php" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>
