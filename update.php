<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata and link to external resources -->
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
        // Include connection to the database
        include "connection.php";
        
        // Include credentials.php to access admin password for validation
        include "credentials.php"; 

        // Check if an 'update' parameter is passed in the URL
        if($_GET['update'])
        {
            $id = $_GET['update']; // Get the ID of the SCP record to update
            
            // Prepare SQL statement to retrieve the existing SCP record by ID
            $recordID = $connection->prepare("SELECT * FROM scp_data WHERE id = ?");
            if(!$recordID)
            {
                // If preparation fails, display error and stop script execution
                echo "<div class='error-message'>Error preparing File for updating.</div>";
                exit;
            }
            
            // Bind the ID parameter to the SQL query
            $recordID->bind_param("i", $id);
            
            // Execute the query to fetch the SCP data
            if($recordID->execute())
            {
                echo "<div class='message'>File ready for updating.</div>";
                
                // Fetch the result and store it in a variable
                $temp = $recordID->get_result();
                $row = $temp->fetch_assoc(); // Get the data of the specific SCP record
            }
            else
            {
                // Display an error message if execution fails
                echo "<div class='error-message'>Error: {$recordID->error}</div>";
            }
        }

        // Check if the form was submitted with the 'update' button
        if(isset($_POST['update']))
        {
            $entered_password = $_POST['admin_password']; // Get the entered password from the form
            $admin_password = $password; // Retrieve the stored admin password from credentials.php
            
            // Compare the entered password with the admin password
            if($entered_password === $admin_password) {
                // If the password is correct, prepare an SQL statement to update the SCP record
                $update = $connection->prepare("UPDATE scp_data SET title=?, object_class=?, image=?, special_containment_procedures=?, description=? WHERE id=?");
                
                // Bind form values to the SQL query (from POST request)
                $update->bind_param("sssssi", $_POST['title'], $_POST['object_class'], $_POST['image'], $_POST['special_containment_procedures'], $_POST['description'], $_POST['id']);
                
                // Execute the update query
                if($update->execute())
                {
                    // If successful, display a success message
                    echo "<div class='message'>File successfully updated to Archive</div>";
                }
                else
                {
                    // If update fails, display the error
                    echo "<div class='error-message'>Error: {$update->error}</div>";
                }
            } else {
                // If the password is incorrect, show an error message
                echo "<div class='error-message'>Incorrect password. Update not allowed.</div>";
            }
        }
    ?>
    <header class="header">
        <!-- Display header image -->
        <img src="images/SCP_Header.png" alt="SCP_banner" style="width:100%;height:auto;">
        <nav>
            <!-- Link to go back to the home page -->
            <a href="index.php">Back to Home page</a>
        </nav>
    </header>
    <div>
        <!-- Form for updating SCP file details -->
        <form method="post" action="update.php" class="form">
            <h1>Update SCP File</h1>
            <br><br>
            
            <!-- Hidden input field to store the SCP record ID -->
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <!-- Input for Title of SCP -->
            <label>Enter Title</label>
            <br>
            <input type="text" name="title" placeholder="Title..." class="form" required value="<?php echo $row['title']; ?>">
            <br><br><br>
            
            <!-- Input for Object Class of SCP -->
            <label>Enter Object Class</label>
            <br>
            <input type="text" name="object_class" placeholder="Object Class..." class="form" value="<?php echo $row['object_class']; ?>">
            <br><br><br>
            
            <!-- Input for Image URL -->
            <label>Enter Image</label>
            <br>
            <input type="text" name="image" placeholder="images/nameofimage.png..." class="form" value="<?php echo $row['image']; ?>">
            <br><br><br>
            
            <!-- Textarea for Special Containment Procedures -->
            <label>Enter Special Containment Procedures</label>
            <br>
            <textarea type="text" name="special_containment_procedures" placeholder="Special Containment Procedures" class="form"><?php echo $row['special_containment_procedures']; ?></textarea>
            <br><br><br>
            
            <!-- Textarea for Description -->
            <label>Enter Description</label>
            <br>
            <textarea type="text" name="description" placeholder="Special Containment Procedures" class="form" ><?php echo $row['description']; ?></textarea>
            <br><br><br>
            
            <!-- Password input field for admin password -->
            <label>Enter Admin Password</label>
            <br>
            <input type="password" name="admin_password" placeholder="Enter password..." class="form" required>
            <br><br>
            
            <!-- Submit button to update the SCP record -->
            <input type="submit" name="update" class="submit-button">
        </form>
    </div>
    <div class="footer" style="display: block;">
        <hr>
        <br>
        <!-- Footer content with external links -->
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
