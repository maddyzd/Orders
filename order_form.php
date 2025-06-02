<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Form</title>
    <style>
        body {background-color: #f4f4f4;}
        p {color: #666}
        h3 {font-size: 25px}
   </style>
</head>
<body>
    <!-- seperate php page header -->
    <?php include 'header.php'; ?>

    <!-- start a form with get method and process_order action-->
    <form id="orderForm" method="get" action="process_order.php">
        <?php
            $id = $_REQUEST['userid'];
            $pw = $_REQUEST['pw'];
        
            //establish connection info
            $db_server = "localhost";//  server
            $db_userid = "umeso890wbaig"; //  user id
            $db_pw = "myuser123"; //  password
            $db_name= "dbehzbbrhwgg7j"; // database

            // Create connection
            $conn = new mysqli($db_server, $db_userid, $db_pw, $db_name);

            // Check connection
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }
            
            $conn->select_db($db);
            
            //select all categories from my database
            $sql = "SELECT name, description, price, image FROM menu";
            $result = $conn->query($sql);

            //for each menu item show name, description, price and image
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div>"; 
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>" . $row['description'] . "<br>";
                    echo "<p>Price: $" . $row['price'] . "<br>";
                    echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "' style='width:100px; height:auto;'>" . "<br>";
                    echo "<label for='" . $row["name"] . "_quantity'>Quantity:</label>";
                    echo "<select id='" . $row['name'] . "_quantity' name='" . $row['name'] . "_quantity'>";

                    for ($i = 0; $i <= 10; $i++) {
                        echo "<option value='" . $i . "'>" . $i . "</option>";
                    }
                    echo "</select>";
                    echo "</div>";
                    echo "<br>";
                }
            }

            // close the connection  
            $conn->close();

        ?>
        <!-- 4 order form fields for the user to enter their first and last name and special instructions
             also add hidden pickuptime  -->
        <div id="endorder">
            <input type="text" id="firstName" name="firstName" placeholder="First Name">
            <input type="text" id="lastName" name="lastName" placeholder="Last Name">
            <br><br>
            <textarea id="specialInstructions" name="specialInstructions" placeholder="Special Instructions"></textarea>
            <br><br>
            <input type="hidden" id="pickupTime" name="pickupTime">
        </div>

        <!-- submit button at bottom of form -->
        <input type="submit" value="Submit Order">

    </form>

   <script>
    // After order form is submitted javascript to validate at least one item ordered
    // and customer name entered
    document.getElementById('orderForm').onsubmit = function(event) {
        // Check if at least one of the items was ordered
        let itemOrdered = false;
        const boxes = document.querySelectorAll('select');
        boxes.forEach(select => {
            if (parseInt(select.value) > 0) {
                itemOrdered = true;
            }
        });

       // If no item was ordered, query to order an item
       if (!itemOrdered) {
           alert('Please order at least one item.');
           event.preventDefault();
           return false;
       }

       // Check if the first name and last name were entered and if not, query user to enter
       const firstName = document.getElementById('firstName').value.trim();
       const lastName = document.getElementById('lastName').value.trim();
       if (!firstName || !lastName) {
           alert('Please enter both your first and last name.');
           event.preventDefault();
           return false;
       }

       // Calculate pickup time
       var time = new Date();
       time.setMinutes(time.getMinutes() + 20); // add 20 minutes to the current time
       //format the time correctly as hour:minute 12 hour clock
       var toformat = new Intl.DateTimeFormat("en-US", {
           hour: '2-digit',
           minute: '2-digit',
           hour12: true
       });
       var pickupTime = toformat.format(time);

       // set pickup time to the hidden pickup time field
       document.getElementById('pickupTime').value = pickupTime;

       // Continue with the form submission
       return true;
   };
</script>

</body>
</html>