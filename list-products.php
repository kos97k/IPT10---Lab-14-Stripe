<?php
require "init.php";

$products = $stripe->products->all();

echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Product List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #f4f4f4);
            padding: 40px;
            margin: 0;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
            max-width: 800px;
            margin: auto;
        }

        li {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            animation: slideUp 0.8s ease-in-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h2 {
            color: #007BFF;
            font-size: 18px;
        }

        p {
            color: #555;
            margin-bottom: 10px;
        }

        img {
            display: block;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>Product List</h1>
    <ul>";

foreach ($products as $product) {
    echo "<li>";
    echo "<h2>Product ID: " . htmlspecialchars($product->id) . "</h2>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($product->name) . "</p>";

    if (!empty($product->images)) {
        $image = array_pop($product->images);
        echo "<p><strong>Image:</strong></p>";
        echo "<img src='" . htmlspecialchars($image) . "' alt='Product Image' width='150' />";
    } else {
        echo "<p><em>No image available</em></p>";
    }

    echo "<p><strong>Price:</strong> ";
    try {
        $price = $stripe->prices->retrieve($product->default_price);
        echo strtoupper($price->currency) . " " . number_format($price->unit_amount / 100, 2);
    } catch (Exception $e) {
        echo "<em>Price not available</em>";
    }
    echo "</p>";
    echo "<hr />";
    echo "</li>";
}

echo "
    </ul>
</body>
</html>";
?>
