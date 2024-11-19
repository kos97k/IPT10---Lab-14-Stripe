<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration Form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #f4f4f4);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
            animation: slideUp 0.8s ease-in-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            animation: fadeIn 1s 0.5s forwards;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
            font-size: 14px;
        }

        input[type="text"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.02);
        }

        button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Registration</h1>
        <form action="create-customer.php" method="post">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
            
            <label for="line1">Address Line 1:</label>
            <input type="text" id="line1" name="line1" required>
            
            <label for="line2">Address Line 2:</label>
            <input type="text" id="line2" name="line2">
            
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
            
            <label for="state">State:</label>
            <input type="text" id="state" name="state">
            
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>
            
            <label for="postal_code">Postal Code:</label>
            <input type="text" id="postal_code" name="postal_code" required>
            
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>



<?php

require "init.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $line1 = $_POST['line1'];
    $line2 = $_POST['line2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $postal_code = $_POST['postal_code'];

    try {
        $customer = $stripe->customers->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => [
                'line1' => $line1,
                'line2' => $line2,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'postal_code' => $postal_code
            ]
        ]);

        echo "<script>
                alert('Registration Successful');
                window.location.href = 'create-customer.php';
              </script>";

    } catch (Exception $e) {
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href = 'create-customer.php';
              </script>";
    }
}
?>
