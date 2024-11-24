<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="background-color: rgb(30, 144, 255)" ;>
    <nav class="navbar navbar-expand-lg" style="background-color:#e3f2fd;">
        <a class="navbar-brand" href="#"><img style="height:50px;" src="d.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Főoldal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_topic.php">Create Topic</a>
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
        <div class="row">
            <div class="col-md-6">
                <?php
                include 'header.php';
                include 'db.php';

                $topic_id = $_GET['id'];

                $stmt = $conn->prepare("SELECT t.title, t.content, t.created_at, u.username FROM topics t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $stmt->bind_result($title, $content, $created_at, $username);
                $stmt->fetch();
                $stmt->close();

                echo "<div class='card mb-3'>";
                echo "<div class='card-header'>" . htmlspecialchars($title) . "</div>";
                echo "<div class='card-body'>";
                echo "<p class='card-text'>" . nl2br(htmlspecialchars($content)) . "</p>";
                echo "<p class='card-text'><small class='text-muted'>Posted by " . htmlspecialchars($username) . " on " . $created_at . "</small></p>";
                echo "</div>";
                echo "</div>";

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $comment_content = $_POST['content'];
                    $username = $_SESSION['username'];

                    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($user_id);
                    $stmt->fetch();
                    $stmt->close();

                    $stmt = $conn->prepare("INSERT INTO comments (topic_id, user_id, content) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $topic_id, $user_id, $comment_content);

                    if ($stmt->execute()) {
                        echo "Comment added successfully.";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                }

                $stmt = $conn->prepare("SELECT c.content, c.created_at, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.topic_id = ? ORDER BY c.created_at DESC");
                $stmt->bind_param("i", $topic_id);
                $stmt->execute();
                $stmt->bind_result($comment_content, $comment_created_at, $comment_username);

                echo "<h5>Hozzászólások:</h5>";
                while ($stmt->fetch()) {
                    echo "<p>@" . htmlspecialchars($comment_username) . ": " . htmlspecialchars($comment_content) . "</p>";
                }
                $stmt->close();
                ?>

                <form method="POST" action="view_topic.php?id=<?php echo $topic_id; ?>">
                    <div class="form-group">
                        <textarea class="form-control" name="content" id="content" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Hozzászólás</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>