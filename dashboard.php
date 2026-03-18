<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
        exit();
        }

        $uid = $_SESSION['user_id'];

        $q = mysqli_query($conn,"
        SELECT subjects.* FROM subjects
        JOIN subject_users ON subjects.id = subject_users.subject_id
        WHERE subject_users.user_id=$uid
        ");
        ?>

        <link rel="stylesheet" href="style.css">

        <div class="box">
        <h2>Select Subject</h2>

        <?php while($s=mysqli_fetch_assoc($q)){ ?>
        <a href="subject.php?id=<?php echo $s['id']; ?>">
        <?php echo $s['name']; ?>
        </a><br><br>
        <?php } ?>

        <a href="logout.php">Logout</a>
        </div>