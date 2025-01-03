<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'uitm_eprofile');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$isAdmin = $_SESSION['role'] === 'admin';

// Fetch profiles from the database
$result = $conn->query("SELECT * FROM profiles LIMIT 11");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">UiTM e-Profile</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">List of Profiles</h2>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Profile Picture</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Faculty</th>
                    <?php if ($isAdmin): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($profile = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if ($profile['profile_picture']): ?>
                                <img src="uploads/<?php echo $profile['profile_picture']; ?>" alt="Profile Picture" class="profile-picture" width="50">
                            <?php else: ?>
                                <img src="uploads/default.png" alt="Default Picture" class="profile-picture" width="50">
                            <?php endif; ?>
                        </td>
                        <td><?php echo $profile['name']; ?></td>
                        <td><?php echo $profile['email']; ?></td>
                        <td><?php echo $profile['phone_number']; ?></td>
                        <td><?php echo $profile['address']; ?></td>
                        <td><?php echo $profile['faculty']; ?></td>
                        <?php if ($isAdmin): ?>
                            <td>
                                <a href="edit_profile.php?id=<?php echo $profile['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_profile.php?id=<?php echo $profile['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php if ($isAdmin): ?>
            <div class="d-flex justify-content-center mt-4">
                <a href="add_profile.php" class="btn btn-success me-3" style="width: 200px; font-size: 1.1rem;">Add New Profile</a>
                <a href="ui_profile_system.php" class="btn btn-secondary" style="width: 200px; font-size: 1.1rem;">Back</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 UiTM e-Profile. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript for enlarging profile pictures -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const images = document.querySelectorAll(".profile-picture");
            images.forEach(image => {
                image.style.cursor = "pointer";
                image.addEventListener("click", function () {
                    const overlay = document.createElement("div");
                    overlay.style.position = "fixed";
                    overlay.style.top = "0";
                    overlay.style.left = "0";
                    overlay.style.width = "100vw";
                    overlay.style.height = "100vh";
                    overlay.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
                    overlay.style.display = "flex";
                    overlay.style.justifyContent = "center";
                    overlay.style.alignItems = "center";
                    overlay.style.zIndex = "1000";

                    const enlargedImg = document.createElement("img");
                    enlargedImg.src = image.src;
                    enlargedImg.style.maxWidth = "90%";
                    enlargedImg.style.maxHeight = "90%";
                    enlargedImg.style.boxShadow = "0 0 15px white";

                    overlay.appendChild(enlargedImg);
                    document.body.appendChild(overlay);

                    // Close overlay on click
                    overlay.addEventListener("click", function () {
                        document.body.removeChild(overlay);
                    });
                });
            });
        });
    </script>
</body>
</html>
