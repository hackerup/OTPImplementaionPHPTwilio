<?php 
    if(!isset($_POST['submit'])) {
        ?>
        <html>
            <body><br/><br/><br/>
                <form action="verify.php" method="post"> 
                    Enter Received OTP: <input type="text" name="otp"><br/><br/>
                    Verify: <input type="submit" name="submit">
                </form>
            </body>
        </html>
        <?php
    } 
?>

<?php
session_start();

include ('db_conn.php');

$phoneNumber = $_SESSION['phoneNumber'];


if(isset($_POST['submit'])) {
    $otp = $_POST['otp'];
    $sql = "SELECT * FROM `users` WHERE `phone_number` = '$phoneNumber' AND `otp` = '$otp'";
    $isOTPCorrect = mysqli_query($connection, $sql) or die(mysqli_error($connection));
   
    if(mysqli_num_rows($isOTPCorrect) > 0) {
        $record = mysqli_fetch_assoc($isOTPCorrect);
        $sql = "UPDATE `users` SET `is_verified`='1' WHERE `phone_number` = '$phoneNumber'";
        $verified = mysqli_query($connection, $sql) or die(mysqli_error($connection));
        echo "Congrats, Your Mobile Number is Verified";die;
    } else {
        echo "Incorrect OTP";die;
    }
}