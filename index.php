<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $u = $_POST['username'];
        $p = $_POST['password'];

            $q = mysqli_query($conn,"SELECT * FROM users WHERE username='$u' AND password='$p'");

                if(mysqli_num_rows($q)){
                        $user = mysqli_fetch_assoc($q);
                                $_SESSION['user_id'] = $user['id'];
                                        header("Location: dashboard.php");
                                                exit();
                                                    } else {
                                                            $error = "Invalid login";
                                                                }
                                                                }
                                                                ?>

                                                                <link rel="stylesheet" href="style.css">

                                                                <div class="box">
                                                                <h2>Login</h2>

                                                                <?php if(isset($error)) echo "<p>$error</p>"; ?>

                                                                <form method="POST">
                                                                <input name="username" placeholder="Username" required>
                                                                <input name="password" type="password" placeholder="Password" required>
                                                                <button name="login">Login</button>
                                                                </form>
                                                                </div>