<?php

require "init.php";

$products = $stripe->products->all();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_products = $_POST['products'] ?? [];
    $line_items = [];

    try {

        foreach ($selected_products as $product_id) {
            $product = $stripe->products->retrieve($product_id);
            $price = $stripe->prices->retrieve($product->default_price);

            $line_items[] = [
                'price' => $price->id,
                'quantity' => 1
            ];
        }


        if (!empty($line_items)) {
            $payment_link = $stripe->paymentLinks->create([
                'line_items' => $line_items
            ]);


            header("Location: " . $payment_link->url);
            exit;
        } else {
            echo "<script>
                    alert('No products selected. Please choose at least one product.');
                    window.location.href = 'generate-payment-link.php'; // Redirect back if no products are selected
                  </script>";
        }

    } catch (Exception $e) {
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Payment Link</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #f9f9f9);
            padding: 40px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
            animation: slideUp 0.8s ease-in-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            animation: fadeIn 1s 0.5s forwards;
        }

        h3 {
            color: #555;
            margin-bottom: 15px;
        }

        label {
            font-size: 16px;
            color: #666;
            display: inline-block;
            margin-left: 5px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .checkbox-container:hover {
            background-color: #f1f1f1;
            border-radius: 6px;
            padding: 5px;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            display: block;
            width: 100%;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(1px);
        }

        input[type="checkbox"] {
            accent-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Generate Payment Link</h1>
        <form action="generate-payment-link.php" method="post">
            <h3>Select Products:</h3>
            <?php foreach ($products as $product): ?>
                <div class="checkbox-container">
                    <input type="checkbox" name="products[]" value="<?php echo htmlspecialchars($product->id); ?>" id="<?php echo htmlspecialchars($product->id); ?>">
                    <label for="<?php echo htmlspecialchars($product->id); ?>"><?php echo htmlspecialchars($product->name); ?></label>
                </div>
            <?php endforeach; ?>
            <button type="submit">Generate Payment Link</button>
        </form>
    </div>
</body>
</html>
