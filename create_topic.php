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
    <?php
    include 'header.php';
    include 'db.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $username = $_SESSION['username'];

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO topics (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);

        if ($stmt->execute()) {
            echo "Siker";
        } else {
            echo "Hiba: " . $stmt->error;
        }

        $stmt->close();
    }
    ?>

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
    <div class="card-md-5">
        <form method="POST" action="create_topic.php">
            <div class="card-body">
                <h5 class="card-title">Téma: <input type="text" name="title" required></h5>
                <p class="card-text"><textarea style="width:350px" ; name="content" required></textarea></p>
                <input type="submit" value="Közzététel" class="btn btn-primary"></input>
            </div>
    </div>
</body>

</html>