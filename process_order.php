<?php
    //seperate php page header
    include 'header.php'; 

    // Establish connection info
    $db_server = "localhost";//  server
    $db_userid = "umeso890wbaig"; //  user id
    $db_pw = "myuser123";//  password
    $db_name = "dbehzbbrhwgg7j"; // database

    // Create connection
    $conn = new mysqli($db_server, $db_userid, $db_pw, $db_name);

    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    //Select name and price from from my database
    $sql = "SELECT name, price FROM menu";
    $result = $conn->query($sql);

    //check if the SQL query returned results 
    if ($result->num_rows > 0) {
        //put the items in an unordered list 
        echo "<ul>";

        //initialize subtotal to 0
        $subtotal = 0;

        //iterate over form
        foreach ($_GET as $key => $quantity) {
            if (strpos($key, '_quantity') !== false && $quantity > 0) { 

                //check if key name includes _quantity and the _quantity is more than 0 so something
                //with that name was ordered
                $name = str_replace('_quantity', '', $key);

                //match the names like they are stored in the database
                $update_name = str_replace("_", " ", $name);

                // Resets the result pointer to the start
                $result->data_seek(0); 

                //fetch each row of query result of name and price for items in database
                while ($row = $result->fetch_assoc()) {

                    //if name in database is name of an item that was ordered get the price info
                    if ($row["name"] === $update_name) {
                        //calculate the price based on how many ordered and add to subtotal
                        $price = $row["price"];
                        $totalForItem = $price * $quantity;
                        $subtotal += $totalForItem;

                        //display name, quanitity, price per item, total price for item ordered
                        echo "<strong style='font-size: 20px;'>" . $update_name . "</strong><br>"; 
                        echo "<strong>Quantity:</strong> " . $quantity . "<br>";
                        echo "<strong>Price:</strong> $" . number_format($price, 2) . "<br>";
                        echo "<strong>Total for item:</strong> $" . number_format($totalForItem,2) . "<br>";
                        echo "<br>";
                        break;
                    }
                }
            }
        }
        echo "</ul>";

        //calculate tax and total based on tax
        $tax = $subtotal * 0.0625;
        $total = $subtotal + $tax;

        //display subtotal, tax, and total for order with 2 digits after decimal place
        echo "<strong>Subtotal:</strong> $" . number_format($subtotal, 2) . "<br>";
        echo "<strong>Tax:</strong> $" . number_format($tax, 2) . "<br>";
        echo "<strong>Total for order:</strong> $" . number_format($total, 2) . "<br>";

        //Get and display pickup time first and last name and special instructions of user
        $pickupTime = $_GET['pickupTime'];
        $firstName = $_GET['firstName'];
        $lastName = $_GET['lastName'];
        $username = $firstName . " " . $lastName; 
        $specialInstructions = $_GET['specialInstructions'];
        echo "<strong>Pickup Time: </strong>" . $pickupTime . "<br>";
        echo"<br>";
        echo "<strong>Order Summary for $firstName $lastName</strong>" . "<br>";   
        echo "<strong>Special Instructions: </strong>" . $specialInstructions . "<br>";

        //calculate time of order to put into database
        $time = new DateTime();
        $time->setTimezone(new DateTimeZone('America/New_York'));
        $orderTime = $time->format('Y-m-d H:i:s');

        //Insert the order into the orders table in the database with username special instructions and order time
        $sql = "INSERT INTO orders (username, special_instructions, order_time) VALUES (?, ?, ?)";
        $result1 = $conn->prepare($sql);
        $result1->bind_param("sss", $username, $specialInstructions, $orderTime);
        $result1->execute();
        $orderId = $conn->insert_id;

        //insert each ordered item into the order_items table in the database with the order id, item name and quantity
        foreach ($_GET as $key => $quantity) {
            if (strpos($key, '_quantity') !== false && $quantity > 0) {
                $itemName = str_replace('_quantity', '', $key);
                $sql = "INSERT INTO order_items (order_id, item_name, quantity) VALUES (?, ?, ?)";
                $result1 = $conn->prepare($sql);
                $result1->bind_param("isi", $orderId, $itemName, $quantity);
                $result1->execute();
            }
        }

        //close the connection
        $conn->close();
    }
?>
