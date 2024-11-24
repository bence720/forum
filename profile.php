<?php
include 'header.php';
include 'db.php';

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT username, email, bio FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $email, $bio);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $new_email = $_POST['email'];
        $new_bio = $_POST['bio'];

        $stmt = $conn->prepare("UPDATE users SET email = ?, bio = ? WHERE username = ?");
        $stmt->bind_param("sss", $new_email, $new_bio, $username);
        if ($stmt->execute()) {
            echo "Profile updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        header("Location: delete_profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>


<body style="background-color: rgb(30, 144, 255)" ;>

    <nav class="navbar navbar-expand-lg" style="background-color:#e3f2fd" ;>
        <a class="navbar-brand" href="#"><img style=height:50px; src="d.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Főoldal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_topic.php">Posztolj!</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Kijelentkezés</a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="container mt-5">
        <h2>Profil</h2>
        <form method="POST" action="profile.php">
            <div class="form-group">
                <label for="username">Felhasználónév:</label>
                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea class="form-control" id="bio" name="bio" required><?php echo htmlspecialchars($bio); ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Profil frissítés</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your profile? This action cannot be undone.');">Profil törlés</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>