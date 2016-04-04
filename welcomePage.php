<!DOCTYPE html>
<html lang="en">


<head>
    <!--
        CTS2445.0M1

        Author: Byron Burdette
        Date: 04/03/2016

        File name: welcomePage.php
    -->

    <title>Welcome</title>
    <meta charset="utf-8">
    
    <style>
    </style>
    
</head>

<body>
    <?php
        $name = $_GET["name"];
        
        echo "<p>Log in successful. Welcome " . $name . ".</p>";
    ?>
</body>

</html>