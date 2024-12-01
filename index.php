<?php
include 'header.php';
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
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
        <div class="row">
            <div class="col-md-6">
                <?php
                $sql = "SELECT t.id, t.title, t.content, t.created_at, u.username FROM topics t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='card mb-3'>";
                        echo "<div class='card-header'>@" . htmlspecialchars($row['username']) . "</div>";
                        echo "<div class='card-body'>";
                        echo "<p class='card-text'><h5 style='padding-top=0px';>" . htmlspecialchars($row['title']) . "</h5></p>";
                        echo "<p class='card-text'>" . htmlspecialchars($row['content']) . "</p>";
                        echo "<p class='card-text'> " . htmlspecialchars($row['created_at']) . "</p>";
                        echo "<a href='view_topic.php?id=" . $row['id'] . "' class='btn btn-primary'>Hozzászólások</a>";

                        if (isset($_SESSION['username']) && $_SESSION['username'] == $row['username']) {
                            echo " <a href='edit_topic.php?id=" . $row['id'] . "' class='btn btn-secondary'>Módosítás</a>";
                            echo " <a href='delete_topic.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Törlés</a>";
                        }

                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning' role='alert'>:(</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>