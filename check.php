<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $order = htmlspecialchars($_POST['order']);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'masitamaramsa@gmail.com';
        $mail->Password = 'ukepdinogzecdsnw'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('masitamaramsa@gmail.com', 'Order Form');
        $mail->addAddress('masitamaramsa@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = "New Order from $name";

        // Format email content
        $lines = explode("\n", $order);
        $mail->Body = "
        <div style='background:#121212; color:#eee; font-family:sans-serif; padding:20px;'>
            <h2 style='border-bottom:1px solid #444;'>Order Information</h2>
            <table style='width:100%; border-collapse:collapse; margin-bottom:20px;'>
                <tr><td style='padding:8px; border:1px solid #444;'>Email</td><td style='padding:8px; border:1px solid #444;'>$email</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Name</td><td style='padding:8px; border:1px solid #444;'>$name</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Order Time</td><td style='padding:8px; border:1px solid #444;'>".date('Y-m-d H:i')."</td></tr>
            </table>

            <h2 style='border-bottom:1px solid #444;'>Order Summary</h2>
            <table style='width:100%; border-collapse:collapse;'>
                <tr style='background:#1e1e1e;'>
                    <th style='padding:10px; border:1px solid #444;'>Item</th>
                    <th style='padding:10px; border:1px solid #444;'>Qty</th>
                    <th style='padding:10px; border:1px solid #444;'>Size</th>
                    <th style='padding:10px; border:1px solid #444;'>Price</th>
                </tr>";

        foreach ($lines as $line) {
            if (strpos($line, 'Item:') !== false) {
                preg_match('/Item: (.*), Qty: (\d+), Size: (.*), Total: \$(.*)/', $line, $matches);
                $mail->Body .= "<tr>
                    <td style='padding:8px; border:1px solid #444;'>".$matches[1]."</td>
                    <td style='padding:8px; border:1px solid #444;'>".$matches[2]."</td>
                    <td style='padding:8px; border:1px solid #444;'>".$matches[3]."</td>
                    <td style='padding:8px; border:1px solid #444;'>$".$matches[4]."</td>
                </tr>";
            }
        }

        $mail->Body .= "
            </table>
            <div style='margin-top:15px; font-size:14px;'>
                <p><strong>".htmlspecialchars($lines[count($lines)-3])."</strong></p>
                <p><strong>".htmlspecialchars($lines[count($lines)-2])."</strong></p>
                <p><strong style='font-size:18px;'>".htmlspecialchars($lines[count($lines)-1])."</strong></p>
            </div>
            <p style='font-size:12px; margin-top:30px; color:#888;'>Sent via PHPMailer</p>
        </div>";

        $mail->send();
        $status = "✅ Order sent successfully!";
    } catch (Exception $e) {
        $status = "❌ Error sending email: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Checkout</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 30px;
    }
    .container {
      background: #fff;
      padding: 20px;
      max-width: 600px;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      margin-top: 0;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      box-sizing: border-box;
      border: 1px solid #ccc;
    }
    button {
      padding: 10px;
      background: #007bff;
      color: #fff;
      border: none;
      width: 100%;
      cursor: pointer;
    }
    button:hover {
      background: #0056b3;
    }
    .status {
      margin-top: 20px;
      color: green;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Order Checkout</h2>
  <form method="POST" action="">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>

    <textarea name="order" rows="8" placeholder="Order Details" required>Item: Subtle Button Ups, Qty: 50, Size: L, Total: $1650.00
Subtotal: $1650.00
Shipping: $2.00
Total: $1652.00</textarea>

    <button type="submit">Send Order</button>
  </form>

  <?php if (!empty($status)): ?>
    <p class="status"><?php echo $status; ?></p>
  <?php endif; ?>
</div>

</body>
</html>
