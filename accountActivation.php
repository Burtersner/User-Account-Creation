<!DOCTYPE html>
<html lang="en">


<head>
    <!--
        CTS2445.0M1

        Author: Byron Burdette
        Date: 04/03/2016

        File name: accountActivation.php
    -->

    <title>Activation</title>
    <meta charset="utf-8">
    
    <style>
    </style>
    
</head>

<body>
    <?php
        include("dbinfo.php");
        
        $connection = mysqli_connect($dbhostname, $dbuser, $dbpass, $dbname[0]);

        if (mysqli_connect_errno())
            echo "Failed to connect: " . mysqli_connect_error();
        else
        {
            $name = $_GET["name"];
            
            if (mysqli_query($connection, "UPDATE UserAccounts SET status = 1 WHERE displayName = '$name'"))
                echo "Thank you. Your account has been succesfully activated.";
            else
                echo "Error: " . mysqli_error($connection);
        }
    ?>
</body>

</html>