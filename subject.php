<?php
session_start();
include "db.php";

$uid = $_SESSION['user_id'];
$sid = $_GET['id'];

// role per subject
$rq = mysqli_query($conn,"
SELECT role FROM subject_users 
WHERE user_id=$uid AND subject_id=$sid
");
$role = mysqli_fetch_assoc($rq)['role'];

// add experiment
if(isset($_POST['add_exp'])){
    mysqli_query($conn,"
        INSERT INTO experiments (subject_id,exp_no,title)
            VALUES ($sid,'$_POST[exp_no]','$_POST[title]')
                ");
                }

                // submit work
                if(isset($_POST['submit'])){
                    $exp = $_POST['exp_id'];
                        $status = $_POST['status'];

                            $file = $_FILES['img'];
                                $name = time()."_".$file['name'];
                                    $path = "uploads/".$name;

                                        move_uploaded_file($file['tmp_name'],$path);

                                            $check = mysqli_query($conn,"
                                                SELECT * FROM submissions 
                                                    WHERE student_id=$uid AND experiment_id=$exp
                                                        ");

                                                            if(mysqli_num_rows($check)){
                                                                    mysqli_query($conn,"
                                                                            UPDATE submissions 
                                                                                    SET status='$status',signature_image='$path'
                                                                                            WHERE student_id=$uid AND experiment_id=$exp
                                                                                                    ");
                                                                                                        } else {
                                                                                                                mysqli_query($conn,"
                                                                                                                        INSERT INTO submissions (student_id,experiment_id,status,signature_image)
                                                                                                                                VALUES ($uid,$exp,'$status','$path')
                                                                                                                                        ");
                                                                                                                                            }

                                                                                                                                                // ===== PROGRESS =====
                                                                                                                                                    $total = mysqli_fetch_assoc(mysqli_query($conn,"
                                                                                                                                                        SELECT COUNT(*) as t FROM experiments WHERE subject_id=$sid
                                                                                                                                                            "))['t'];

                                                                                                                                                                $done = mysqli_fetch_assoc(mysqli_query($conn,"
                                                                                                                                                                    SELECT COUNT(*) as d FROM submissions s
                                                                                                                                                                        JOIN experiments e ON s.experiment_id=e.id
                                                                                                                                                                            WHERE s.student_id=$uid AND e.subject_id=$sid AND s.status='complete'
                                                                                                                                                                                "))['d'];

                                                                                                                                                                                    $percent = ($total>0)?round(($done/$total)*100):0;

                                                                                                                                                                                        $checkp = mysqli_query($conn,"
                                                                                                                                                                                            SELECT * FROM progress WHERE student_id=$uid AND subject_id=$sid
                                                                                                                                                                                                ");

                                                                                                                                                                                                    if(mysqli_num_rows($checkp)){
                                                                                                                                                                                                            mysqli_query($conn,"
                                                                                                                                                                                                                    UPDATE progress SET completed=$done,total=$total,percentage=$percent
                                                                                                                                                                                                                            WHERE student_id=$uid AND subject_id=$sid
                                                                                                                                                                                                                                    ");
                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                mysqli_query($conn,"
                                                                                                                                                                                                                                                        INSERT INTO progress (student_id,subject_id,completed,total,percentage)
                                                                                                                                                                                                                                                                VALUES ($uid,$sid,$done,$total,$percent)
                                                                                                                                                                                                                                                                        ");
                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                            }

                                                                                                                                                                                                                                                                            // subject
                                                                                                                                                                                                                                                                            $sub = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM subjects WHERE id=$sid"));
                                                                                                                                                                                                                                                                            ?>

                                                                                                                                                                                                                                                                            <link rel="stylesheet" href="style.css">

                                                                                                                                                                                                                                                                            <div class="box">
                                                                                                                                                                                                                                                                            <h2><?php echo $sub['name']; ?></h2>

                                                                                                                                                                                                                                                                            <?php if($role=='teacher'){ ?>
                                                                                                                                                                                                                                                                            <form method="POST">
                                                                                                                                                                                                                                                                            <input name="exp_no" placeholder="Exp No">
                                                                                                                                                                                                                                                                            <input name="title" placeholder="Title">
                                                                                                                                                                                                                                                                            <button name="add_exp">Add Experiment</button>
                                                                                                                                                                                                                                                                            </form>
                                                                                                                                                                                                                                                                            <?php } ?>
                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                            <?php
                                                                                                                                                                                                                                                                            $exp = mysqli_query($conn,"SELECT * FROM experiments WHERE subject_id=$sid");

                                                                                                                                                                                                                                                                            while($e=mysqli_fetch_assoc($exp)){
                                                                                                                                                                                                                                                                            ?>

                                                                                                                                                                                                                                                                            <div class="box">
                                                                                                                                                                                                                                                                            <h3>Exp <?php echo $e['exp_no']." - ".$e['title']; ?></h3>

                                                                                                                                                                                                                                                                            <?php if($role=='student'){ ?>
                                                                                                                                                                                                                                                                            <form method="POST" enctype="multipart/form-data">
                                                                                                                                                                                                                                                                            <input type="hidden" name="exp_id" value="<?php echo $e['id']; ?>">

                                                                                                                                                                                                                                                                            <select name="status">
                                                                                                                                                                                                                                                                            <option value="rough">Rough</option>
                                                                                                                                                                                                                                                                            <option value="complete">Complete</option>
                                                                                                                                                                                                                                                                            </select>

                                                                                                                                                                                                                                                                            <input type="file" name="img" required>
                                                                                                                                                                                                                                                                            <button name="submit">Submit</button>
                                                                                                                                                                                                                                                                            </form>
                                                                                                                                                                                                                                                                            <?php } ?>

                                                                                                                                                                                                                                                                            <?php if($role=='teacher'){ 

                                                                                                                                                                                                                                                                            $q = mysqli_query($conn,"
                                                                                                                                                                                                                                                                            SELECT users.name, submissions.status, submissions.signature_image
                                                                                                                                                                                                                                                                            FROM users
                                                                                                                                                                                                                                                                            JOIN subject_users ON users.id=subject_users.user_id
                                                                                                                                                                                                                                                                            LEFT JOIN submissions 
                                                                                                                                                                                                                                                                            ON users.id=submissions.student_id 
                                                                                                                                                                                                                                                                            AND submissions.experiment_id=".$e['id']."
                                                                                                                                                                                                                                                                            WHERE subject_users.subject_id=$sid AND subject_users.role='student'
                                                                                                                                                                                                                                                                            ");

                                                                                                                                                                                                                                                                            while($s=mysqli_fetch_assoc($q)){
                                                                                                                                                                                                                                                                            echo $s['name']." - ";

                                                                                                                                                                                                                                                                            if($s['status']){
                                                                                                                                                                                                                                                                            echo $s['status']."<br>";
                                                                                                                                                                                                                                                                            echo "<img src='".$s['signature_image']."' width='120'><br>";
                                                                                                                                                                                                                                                                            }else{
                                                                                                                                                                                                                                                                            echo "Pending<br>";
                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                            echo "<br>";
                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                            } ?>

                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                            <?php } ?>

                                                                                                                                                                                                                                                                            <div class="box">
                                                                                                                                                                                                                                                                            <h3>Progress</h3>

                                                                                                                                                                                                                                                                            <?php
                                                                                                                                                                                                                                                                            $pq = mysqli_query($conn,"
                                                                                                                                                                                                                                                                            SELECT users.name, progress.percentage
                                                                                                                                                                                                                                                                            FROM progress
                                                                                                                                                                                                                                                                            JOIN users ON users.id=progress.student_id
                                                                                                                                                                                                                                                                            WHERE progress.subject_id=$sid
                                                                                                                                                                                                                                                                            ");

                                                                                                                                                                                                                                                                            while($p=mysqli_fetch_assoc($pq)){
                                                                                                                                                                                                                                                                            echo $p['name']." - ".$p['percentage']."%<br>";
                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                            ?>
                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                            <a href="dashboard.php">Back</a>