<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SCP PHP CRUD Website</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&family=Special+Elite&display=swap"
        rel="stylesheet">
</head>
<body>
    <?php 
        include "connection.php";
        
        if(isset($_POST['submit']))
        {
            //write a preapared statement to insert data
            $insert = $connection->prepare("insert into scp_data(title, object_class, image, special_containment_procedures, description) values(?,?,?,?,?)");
            $insert->bind_param("sssss", $_POST['title'], $_POST['object_class'], $_POST['image'], $_POST['special_containment_procedures'], $_POST['description']);
            
            if($insert->execute())
            {
                echo "
                    <div class='message'>File successfully Archived</div>
                ";
            }
            else
            {
                echo "
                    <div class='error-message'>Error: {$insert->error}</div>
                ";
            }
        }
    ?>
    <header class="header">
        <img src="images/SCP_Header.png" alt="SCP_banner" style="width:100%;height:auto;">
        <nav>
            <a href="index.php">Back to Home page</a>
        </nav>
    </header>
    <div>
        <form method="post" action="create.php" class="form">
            <h1>Create a new SCP File</h1>
            <br><br>
            
            <label>Enter Title</label>
            <br>
            <input type="text" name="title" placeholder="Title..." class="form" required>
            <br><br><br>
            
            <label>Enter Object Class</label>
            <br>
            <input type="text" name="object_class" placeholder="Object Class..." class="form">
            <br><br><br>
            
            <label>Enter Image URL</label>
            <br>
            <input type="text" name="image" class="form" placeholder="images/nameofimage.png...">
            <br><br><br>
            
            <label>Enter Special Containment Procedures</label>
            <br>
            <textarea type="text" name="special_containment_procedures" placeholder="Special Containment Procedures..." class="form"></textarea>
            <br><br><br>
            
            <label>Enter Description</label>
            <br>
            <textarea type="text" name="description" placeholder="Description..." class="form"></textarea>
            <br><br><br>
            
            <input type="submit" name="submit" class="submit-button">
        </form>
    </div>
    <div class="footer" style="display: block;">
        <hr>
        <br>
        <!-- Footer content with links -->
        Powered by <a href="http://www.github.com">Github.com</a>
        &nbsp;|
        <a href="https://support.github.com/" id="github-help-button">Help</a>
        &nbsp;|
        <a href="https://docs.github.com/en/site-policy/github-terms/github-terms-of-service"
            id="github-tos-button">Terms of Service</a>
        &nbsp;|
        <a href="https://docs.github.com/en/site-policy/privacy-policies/github-general-privacy-statement"
            id="github-privacy-button">Privacy</a>
        &nbsp;|
        <a href="https://docs.github.com/en/site-policy/security-policies/github-bug-bounty-program-legal-safe-harbor"
            id="bug-report-button" onclick="">Report a bug</a>
    </div>
</body>
</html>