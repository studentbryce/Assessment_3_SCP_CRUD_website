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
    
    <!-- JavaScript for handling delete confirmation with password -->
    <script>
        // Function to prompt the user for a password when they attempt to delete a data entry
        function confirmDelete(deleteUrl) {
            // Ask for the admin password through a prompt
            let password = prompt("Please enter the admin password to delete this file:");
            
            // If a password is entered, proceed with the delete action
            if (password !== null && password !== "") {
                // Redirect to the delete URL with the password included as a query parameter
                window.location.href = deleteUrl + "&password=" + encodeURIComponent(password);
            } else {
                // If no password is entered, alert the user that it is required
                alert("Password is required to delete the file.");
            }
        }
    </script>
</head>
<body>
    <?php
    // Include the database connection and credentials file
    include "connection.php"; 
    include "credentials.php"; // Contains the admin password
    
    // Check if a delete request is made and if the password is provided
    if (isset($_GET['delete']) && isset($_GET['password'])) {
        // Retrieve the ID of the file to delete and the entered password
        $delID = $_GET['delete'];
        $enteredPassword = $_GET['password'];
        
        // Check if the entered password matches the admin password stored in credentials.php
        if ($enteredPassword === $password) {
            // If the password is correct, prepare the delete query to remove the record from the database
            $delete = $connection->prepare("DELETE FROM scp_data WHERE id=?");
            $delete->bind_param("i", $delID); // Bind the ID parameter
            
            // Execute the query and check if the deletion was successful
            if ($delete->execute()) {
                // Show success message if the file was deleted
                echo "<div class='message'>File Deleted from Archives</div>";
            } else {
                // Show an error message if there was a problem with the deletion
                echo "<div class='error-message'>Error deleting file from Archives {$delete->error}.</div>";
            }
        } else {
            // If the password is incorrect, display an error message
            echo "<div class='error-message'>Incorrect password. File not deleted.</div>";
        }
    }
    ?>
    <!-- Header section -->
    <header class="header">
        <img src="images/SCP_Header.png" alt="SCP_banner">
        <nav>
            <?php 
            // Include the database connection
            include "connection.php"; 
            
            // Check if there are SCP records in the database
            if($result->num_rows > 0):
            ?>
            <!-- Static navigation links for the website -->
            <a href="index.php">Home</a>
            <div class="dropdown">
                <!-- Dropdown menu for SCP Archives -->
                <button class="dropbtn">SCP Archives</button>
                <div class="dropdown-content">
                    <?php 
                    // Loop through the SCP records and create a link for each title
                    foreach($result as $link): ?>
                        <!-- Link to view specific SCP record based on its title -->
                        <a href="index.php?link=<?php echo urlencode($link['title']); ?>">
                            <?php echo htmlspecialchars($link['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <!-- Link to add a new file to the archives -->
            <a href="create.php">Add file to Archives</a>
        </nav>
    </header>
    
    <!-- Main content div of the website pages -->
    <div class="content">
        <?php
            // Check if a specific SCP title has been clicked (via GET parameter 'link')
            if (isset($_GET['link'])) {
                // Sanitize the GET parameter to prevent SQL injection
                $title = $connection->real_escape_string($_GET['link']);
                
                // Query the database for the selected SCP title
                $scp = $connection->query("SELECT * FROM scp_data WHERE title = '$title'");
                
                // If the query returned results, display the SCP record
                if ($scp->num_rows > 0) {
                    $array = $scp->fetch_assoc(); // Fetch the record as an associative array
                    
                    // Generate the URLs for updating and deleting the SCP record
                    $update = "update.php?update=" . $array['id'];
                    $delete = "index.php?delete=" . $array['id'];
                    
                    // Display the SCP record details
                    echo "
                        <h1>" . htmlspecialchars($array['title']) . "</h1> <!-- Display the SCP title -->
                        <h2>" . htmlspecialchars($array['object_class']) . "</h2> <!-- Display the object class -->
                        <p>
                            <img src='" . htmlspecialchars($array['image']) . "' alt='" . htmlspecialchars($array['title']) . "'> <!-- Display the SCP image -->
                        </p>
                        <h3>Special Containment Procedures</h3>
                        <p>" . nl2br(htmlspecialchars($array['special_containment_procedures'])) . "</p> <!-- Display the containment procedures -->
                        <h3>Description</h3>
                        <p>" . nl2br(htmlspecialchars($array['description'])) . "</p> <!-- Display the description -->
                        
                        <p>
                            <!-- Link to update the SCP file -->
                            <a href='{$update}' class='button'>Update File</a>
                            <!-- Button to delete the SCP file, triggers the confirmDelete function -->
                            <a href='#' onclick=\"confirmDelete('{$delete}')\" class='button'>Delete File</a>
                        </p>
                    ";
                } else {
                    // If no records were found for the selected SCP, display a message
                    echo "<p>No records found for the selected SCP title.</p>";
                }
            } else {
                // If no specific SCP is selected, display all records in a grid layout
                $scpRecords = $connection->query("SELECT id, title, image FROM scp_data ORDER BY id ASC");

                if ($scpRecords->num_rows > 0) {
                    echo "<h1>SCP Foundation</h1>";
                    echo "<h2>Secure, Contain, Protect</h2>";
                    
                    // Initialize a counter to arrange SCPs in rows of two
                    $counter = 0;
                    echo '<div class="flexrow">';
                    
                    // Loop through the SCP records and display them
                    while($scp = $scpRecords->fetch_assoc()) {
                        if ($counter % 2 == 0 && $counter > 0) {
                            // Close the previous row and start a new one after every two SCPs
                            echo '</div><div class="flexrow">';
                        }
                        
                        // Display each SCP as a clickable title with an image
                        echo '
                        <div class="titlesubject">
                            <h3>' . htmlspecialchars($scp['title']) . '</h3> <!-- SCP Title -->
                            <a href="index.php?link=' . urlencode($scp['title']) . '">
                                <img src="' . htmlspecialchars($scp['image']) . '" alt="' . htmlspecialchars($scp['title']) . '" class="nav-image"> <!-- SCP Image -->
                            </a>
                        </div>
                        ';

                        $counter++;
                    }
                    echo '</div>'; // Close the row
                } else {
                    // If no SCP records are found in the database, display a message
                    echo "<p>No SCP records found in the archive.</p>";
                }
            }
        ?>
    </div>
    <div class="footer">
        <hr>
        <br>
        <!-- Footer section with external links -->
        Powered by <a href="http://www.github.com">Github.com</a>
        &nbsp;|
        <a href="https://support.github.com/">Help</a>
        &nbsp;|
        <a href="https://docs.github.com/en/site-policy/github-terms/github-terms-of-service">Terms of Service</a>
        &nbsp;|
        <a href="https://docs.github.com/en/site-policy/privacy-policies/github-general-privacy-statement">Privacy</a>
        &nbsp;|
        <a href="https://docs.github.com/en/site-policy/security-policies/github-bug-bounty-program-legal-safe-harbor">Report a bug</a>
    </div>
</body>
</html>
