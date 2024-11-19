<?php

require "init.php";

$customers = $stripe->customers->all(['limit' => 10]);
$products = $stripe->products->all();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer'];
    $selected_products = $_POST['products'] ?? [];

    try {

        $invoice = $stripe->invoices->create([
            'customer' => $customer_id
        ]);

        foreach ($selected_products as $product_id) {
            $product = $stripe->products->retrieve($product_id);
            $stripe->invoiceItems->create([
                'customer' => $customer_id,
                'price' => $product->default_price,
                'invoice' => $invoice->id
            ]);
        }

        $stripe->invoices->finalizeInvoice($invoice->id);
        $invoice = $stripe->invoices->retrieve($invoice->id);

        echo "<script>
                alert('Invoice generated successfully!');
              </script>";

        echo "<h2>Invoice Created Successfully</h2>";
        echo "<a href='" . htmlspecialchars($invoice->invoice_pdf) . "' target='_blank'>
                <button>Download Invoice PDF</button>
              </a>";
        echo "<a href='" . htmlspecialchars($invoice->hosted_invoice_url) . "' target='_blank'>
                <button>Go to Payment Page</button>
              </a>";
        
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
    <title>Generate Invoice</title>
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

        label {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
            display: block;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            transition: border-color 0.3s;
        }

        select:focus {
            border-color: #007BFF;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }

        .checkbox-container:hover {
            background-color: #eef7ff;
        }

        .checkbox-container input[type="checkbox"] {
            margin-right: 10px;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin: 5px;
            width: 100%;
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
    <div class="form-container">
        <h1>Generate Invoice</h1>
        <form action="generate-invoice.php" method="post">
            <label for="customer">Select Customer:</label>
            <select name="customer" id="customer" required>
                <option value="" disabled selected>Choose a customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo htmlspecialchars($customer->id); ?>">
                        <?php echo htmlspecialchars($customer->name . ' (' . $customer->email . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h3>Select Products:</h3>
            <?php foreach ($products as $product): ?>
                <div class="checkbox-container">
                    <input type="checkbox" name="products[]" value="<?php echo htmlspecialchars($product->id); ?>" id="<?php echo htmlspecialchars($product->id); ?>">
                    <label for="<?php echo htmlspecialchars($product->id); ?>"><?php echo htmlspecialchars($product->name); ?></label>
                </div>
            <?php endforeach; ?>

            <button type="submit">Generate Invoice</button>
        </form>
    </div>
</body>
</html>
