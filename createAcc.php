<!DOCTYPE html>
<html lang="en">


<head>
    <!--
        CTS2445.0M1

        Author: Byron Burdette
        Date: 04/03/2016

        File name: createAcc.php
    -->

    <title>Account Creation</title>
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
                $name = $_POST['name'];
                $password = $_POST['password'];
                
                $validate = true;
                $errors = array();
                
                
                $results = mysqli_query($connection, "select * from UserAccounts where displayName = '$name'");
                if (mysqli_num_rows($results) >= 1)
                {
                    $errors[] = "username already exists";
                    $validate = false;
                }
                
                $results = mysqli_query($connection, "select * from UserAccounts where email = '$email'");
                if (mysqli_num_rows($results) >= 1)
                {
                    $errors[] = "email address already in use";
                    $validate = false;
                }
                
                if ($password != $_POST['vPassword'])
                {
                    $errors[] = "passwords do not match";
                    $validate = false;
                }
                else if (!passwordTest1($password))
                {
                    $errors[] = "your password needs to include " . (implode (", ", passwordTest2($password)));
                    $validate = false;
                }
                
                
                if ($validate)
                {
                    $password = hash('sha512', $password);
                    
                    if (mysqli_query($connection, "INSERT INTO UserAccounts (email, displayName, hashPassword, status) VALUES ('$email', '$name', '$password', 0)"))
                    {
                        $to = $email;
                        $subject = "User Account Verification";
                        $message = "Thank you for creating your account. Click the URL below to activate your account.";
                        $url = "https://cts.gruv.org/burdette/writeable/12.%20Users%20&%20Passwords/accountActivation.php?name=" . $name;
                        $headers = 'From: noreply@cts.gruv.org' . "\r\n" .
                                    'Reply-To: ' . $to . "\r\n";
                        $body = array();
                        
                        $body[] = 'name: ' . $name;
                        $body[] = 'message: ' . $message;
                        $body[] = $url;

                        mail($to, $subject, implode("\r\n\r\n", $body), $headers);
                        
                        header("Location: emailVerification.html");
                    }
                    else
                    {
                        echo "Error: " . mysqli_error($connection);
                        $_POST = array();
                    }
                }
                else
                {
                    echo implode(", ", $errors);
                    $_POST = array();
                }
                
                
                mysqli_close($connection);
            }
        }
        
        
        
        
        function passwordTest1($password)
            {
                if (preg_match('/\s/', $password))
                    return false;
                else if (preg_match('/((?=.*\d)|(?=.*\W+))(?=.*[A-Z])(?=.*[a-z]).*$/', $password))
                    return true;
                else
                    return false;
            }
            
            function passwordTest2($password)
            {
                $testsFailed = array();
                
                if (!preg_match('/(?=.*[a-z])/', $password))
                    $testsFailed[] = "lowercase letters";
                if (!preg_match('/(?=.*[A-Z])/', $password))
                    $testsFailed[] = "uppercase letters";
                if (!preg_match('/(?=.*\W+)/', $password))
                    $testsFailed[] = "special characters";
                if (!preg_match('/(?=.*\d)/', $password))
                    $testsFailed[] = "numbers";
                
                return $testsFailed;
            }
    ?>
    
    <br>
    
    <form action="createAcc.php" method="post">
        <p>display name: <input type="text" name="name" size="40" required></p>
        <p>email: <input type="email" name="email" size="40" required></p>
        <p>password: <input type="password" pattern=".{8,16}" name="password" required></p>
        <p>verify password: <input type="password" pattern=".{8,16}" name="vPassword" required></p>
        <input type="submit" name="submit" value="submit"></p>
    </form>

</body>

</html>