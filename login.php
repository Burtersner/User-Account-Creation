<!DOCTYPE html>
<html lang="en">


<head>
    <!--
        CTS2445.0M1

        Author: Byron Burdette
        Date: 04/03/2016

        File name: login.php
    -->

    <title>log in</title>
    <meta charset="utf-8">
    
    <style>
    </style>
    
</head>

<body>
    <?php
        include("dbinfo.php");
        
        if(isset($_POST['submit']))
        {
            $connection = mysqli_connect($dbhostname, $dbuser, $dbpass, $dbname[0]);

            if (mysqli_connect_errno())
                echo "Failed to connect: " . mysqli_connect_error();
            else
            {
                $email = $_POST['email'];
                $password = hash('sha512', $_POST['password']);
                
                $results = mysqli_query($connection, "select * from UserAccounts where email = '$email'");
                
                if (mysqli_num_rows($results) == 0)
                    echo "user does not exits";
                else
                {
                    $record = mysqli_fetch_assoc($results);
                    
                    if ($password != $record['hashPassword'])
                        echo "incorrect password";
                    else if ($record['status'] == 0)
                        echo "Account still requires activation. Please visit the link provided in your email to activate your account.";
                    else
                        header("Location: welcomePage.php?name=" . $record['displayName']);
                }
            }
        }
    ?>
    
    
    
    
    <form action="login.php" method="post">
        <p>email: <input type="email" name="email" size="40" required></p>
        <p>password: <input type="password" pattern=".{8,16}" name="password" required></p>
        <input type="submit" name="submit" value="submit"></p>
    </form>
</body>

</html>