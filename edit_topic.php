<?php
include 'header.php';
include 'db.php';

$post_id = $_GET['id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT t.title, t.content, u.username FROM topics t JOIN users u ON t.user_id = u.id WHERE t.id = ? AND u.username = ?");
$stmt->bind_param("is", $post_id, $username);
$stmt->execute();
$stmt->bind_result($title, $content, $post_owner);
$stmt->fetch();
$stmt->close();

if ($post_owner !== $username) {
    echo "You do not have permission to edit this post.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_title = $_POST['title'];
    $new_content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE topics SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_title, $new_content, $post_id);
    if ($stmt->execute()) {
        echo "Post updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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
        <h2>Poszt módosítása</h2>
        <form method="POST" action="edit_topic.php?id=<?php echo $post_id; ?>">
            <div class="form-group">
                <label for="title">Téma:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>" required>
            </div>
            <div class="form-group">
                <label for="content"></label>
                <textarea class="form-control" id="content" name="content" required><?php echo $content; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Módosít</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>