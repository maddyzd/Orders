<?php
    // Include the header
    include 'header.php'; 

    // Database connection info
    $db_server = "localhost"; //  server
    $db_userid = "umeso890wbaig"; //userid
    $db_pw = "myuser123"; //  password
    $db_name = "dbehzbbrhwgg7j";// database

    // Create connection
    $conn = new mysqli($db_server, $db_userid, $db_pw, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch all past orders from orders table and order_items table
    $sql = "SELECT * FROM orders JOIN order_items ON orders.order_id = order_items.order_id";
    $result = $conn->query($sql);

    //display all previous order ids, usernames, item names, quanityt, special instructions, ordertime
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Order ID: " . $row['order_id'] . "<br>";
            echo "User: " . $row['username'] . "<br>";
            echo "Item: " . $row['item_name'] . "<br>";
            echo "Quantity: " . $row['quantity'] . "<br>";
            echo "Special Instructions: " . $row['special_instructions'] . "<br>";
            echo "Order Time: " . $row['order_time'] . "<br><hr>";
        }
    } 

    //close connection
    $conn->close();
?>