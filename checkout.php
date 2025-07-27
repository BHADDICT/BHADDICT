<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $order = htmlspecialchars($_POST['order']);
$instagram = htmlspecialchars($_POST['instagram'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$address = htmlspecialchars($_POST['address'] ?? '');
$region = htmlspecialchars($_POST['region'] ?? '');
$province = htmlspecialchars($_POST['province'] ?? '');
$payment = htmlspecialchars($_POST['payment'] ?? '');

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
           <h2 style='border-bottom:1px solid #444;'>Customer Information</h2>
            <table style='width:100%; border-collapse:collapse; margin-bottom:20px;'>
                <tr><td style='padding:8px; border:1px solid #444;'>Email</td><td style='padding:8px; border:1px solid #444;'>$email</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Name</td><td style='padding:8px; border:1px solid #444;'>$name</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Instagram</td><td style='padding:8px; border:1px solid #444;'>$instagram</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Phone</td><td style='padding:8px; border:1px solid #444;'>$phone</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Address</td><td style='padding:8px; border:1px solid #444;'>$address</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Region</td><td style='padding:8px; border:1px solid #444;'>$region</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Province</td><td style='padding:8px; border:1px solid #444;'>$province</td></tr>
                <tr><td style='padding:8px; border:1px solid #444;'>Payment</td><td style='padding:8px; border:1px solid #444;'>$payment</td></tr>
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
        header("Location: thankyou.php");
        exit();

    } catch (Exception $e) {
        // ❌ Show error
        echo "Error sending email: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Bhaddict</title>
  <link rel="icon" href="./img/logay.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
      body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .checkout-container {
      display: flex;
      flex-wrap: wrap;
      max-width: 1200px;
      margin: 30px auto;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .checkout-left, .checkout-right {
      padding: 20px;
    }

    .checkout-left {
      flex: 2;
      min-width: 300px;
    }

    .checkout-right {
      flex: 1;
      background: #fafafa;
      border-left: 1px solid #ddd;
      min-width: 250px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      box-sizing: border-box;
    }

    .name-fields, .small-fields {
      display: flex;
      gap: 10px;
    }

    .name-fields input, .small-fields input, .small-fields select {
      flex: 1;
    }

    .payment-box {
      background: #e6f7ff;
      padding: 10px;
      margin: 10px 0;
    }

  h2 {
    font-weight: 600;
    margin-bottom: 5px;
  }

  .subtitle {
    color: #6e6f72;
    font-size: 14px;
    margin-bottom: 20px;
  }

.payment-card {
  border: 1px solid #3b5998;
  border-radius: 6px;
  max-width: 500px;
  overflow: hidden;
  margin: 20px auto; /* center horizontally + space above and below */
  gap: 20px;
}



  .payment-header {
    background-color: #e7f0ff;
    border-bottom: 1px solid #3b5998;
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .payment-header strong {
    font-weight: 600;
    font-size: 16px;
    color: #3b5998;
  }

  .payment-icons {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .payment-icons img {
    height: 26px;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgb(0 0 0 / 0.1);
  }

  .payment-icons .more {
    font-weight: 600;
    font-size: 14px;
    color: #3b5998;
    padding: 0 8px;
    background: #d8e5ff;
    border-radius: 4px;
    user-select: none;
  }

  .payment-body {
    background: #f7f7f7;
    padding: 40px 20px;
    text-align: center;
    color: #505050;
    font-size: 14px;
  }

  .payment-body svg {
    fill: #a0a0a0;
    width: 70px;
    height: 50px;
    margin-bottom: 18px;
  }
    .billing-address label {
      display: block;
      margin: 5px 0;
    }

    button {
      background: #0070f3;
      color: white;
      border: none;
      padding: 12px;
      width: 100%;
      cursor: pointer;
    }

    button:hover {
      background: #005bb5;
    }

    .order-summary {
      padding: 10px;
    }

    .order-summary .item {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
    }

    .discount-code {
      width: 70%;
      padding: 8px;
      margin-top: 10px;
    }

    .apply-btn {
      width: 25%;
      padding: 8px;
      background: #555;
      color: #fff;
      border: none;
      margin-left: 5%;
    }

    .apply-btn:hover {
      background: #333;
    }

    .summary {
      margin-top: 10px;
    }
    #orderSummary {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 0 10px rgb(0 0 0 / 0.05);
  max-width: 400px;
  font-family: Arial, sans-serif;
  color: #111;
}

#orderSummary .item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px 0;
  border-bottom: 1px solid #eee;
  position: relative;
}

#orderSummary .item:last-child {
  border-bottom: none;
}

#orderSummary .item img {
  width: 64px;
  height: 64px;
  object-fit: cover;
  border-radius: 12px;
  background: #f7f7f7;
  position: relative;
  flex-shrink: 0;
}

#orderSummary .item .qty-badge {
  position: absolute;
  top: -6px;
  left: 48px;
  background: #666;
  color: #fff;
  font-size: 12px;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  line-height: 20px;
  text-align: center;
  font-weight: 600;
  box-shadow: 0 0 4px rgba(0,0,0,0.15);
}

#orderSummary .item-details {
  flex: 1;
}

#orderSummary .item-details strong {
  font-weight: 600;
  font-size: 15px;
  line-height: 1.2;
  display: block;
  margin-bottom: 4px;
}

#orderSummary .item-details .sizes {
  font-size: 13px;
  color: #666;
  letter-spacing: 0.3px;
}

#orderSummary .item-price {
  font-weight: 600;
  font-size: 15px;
  min-width: 80px;
  text-align: right;
  white-space: nowrap;
}

.discount-code {
  width: 100%;
  padding: 12px 15px;
  margin: 20px 0 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
}

.apply-btn {
  width: 100%;
  padding: 12px;
  background: #f2f2f2;
  color: #444;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.apply-btn:hover {
  background: #ddd;
}

.summary {
  font-weight: 600;
  font-size: 16px;
  margin-top: 10px;
  border-top: 1px solid #eee;
  padding-top: 15px;
}

.summary div {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.summary div.total {
  font-size: 18px;
  font-weight: 700;
}

.shipping-info {
  font-size: 13px;
  color: #666;
  display: flex;
  justify-content: space-between;
  margin-top: 5px;
  cursor: help;
}
.payment-method {
  display: flex;
  gap: 20px;
  margin: 10px 0;
}

.payment-method label {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 14px;
  color: #333;
  cursor: pointer;
}

.payment-method input[type="radio"] {
  accent-color: #0070f3; /* modern browsers, changes radio button color */
  width: 16px;
  height: 16px;
}


    @media (max-width: 768px) {
      .checkout-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<div class="checkout-container">
  <div class="checkout-left">
    <h2>Contact</h2>
    <input type="email" id="email" placeholder="Email" required />

    <h2>Delivery</h2>
    <select id="regionSelect">
      <option value="">Region</option>
      <option value="MY">Phnom Penh</option>
      <option value="pr">Provinces</option>
    </select>
    <div id="provinceInputContainer" style="display:none;">
      <input type="text" id="provinceInput" placeholder="Write your province" />
    </div>
    <div class="name-fields">
      <input type="text" id="firstName" placeholder="First name" />
      <input type="text" id="lastName" placeholder="Last name" />
    </div>
    <input type="text" id="instagramInput" placeholder="Instagram username" />
    <input type="text" id="address" placeholder="Address" />
    <input type="tel" id="phone" placeholder="Phone" />

    <h2>Payment</h2>
    <div class="payment-method">
      <label><input type="radio" name="paymentMethod" value="paid" /> Paid </label>
    </div>

    <div class="payment-card">
      <div class="payment-header">
        <strong>Payment Gateway</strong>
        <div class="payment-icons">
          <img src="./img/aba.jpg" alt="ABA" />
          <img src="./img/ac.png" alt="Acleda" style="height: 20px;" />
          <div class="more">+2</div>
        </div>
      </div>
      <div class="payment-body">
        <p>After "scanning", please send the payment proof to our Telegram link below.</p>
        <p><strong id="payment-total">Total Amount: $0.00</strong></p>
        <img src="./img/qrcode.JPG" alt="ABA QR Code" style="max-width: 200px; margin: 10px auto; display: block;">
        <p style="text-align: center; margin-top: 10px;">
          <a href="https://t.me/Chea_mengheng" target="_blank" style="color: #007bff; text-decoration: underline;">
            📩 @bhaddict
          </a>
        </p>
      </div>
    </div>

    <button id="payNow">Confirm Order</button>
    <p style="margin-top:15px; color:green;"><?php echo $status; ?></p>
  </div>

  <div class="checkout-right" id="orderSummary">
    <!-- Order summary will be rendered here -->
  </div>
</div>

<script>
window.addEventListener('load', function () {
  const deliveryFee = 2.00;
  let discountRate = 0;

  if (!localStorage.getItem("cartData")) {
    localStorage.setItem("cartData", JSON.stringify([
      {
        id: 1,
        name: "Réndezvouz “ Subtle ” Button Ups",
        price: 33.00,
        image: "./img/bo1.JPG",
        qty: 1,
        size: "M"
      },
      {
        id: 2,
        name: "Réndezvouz “ Subtle ” Trousers",
        price: 35.00,
        image: "./img/pro3.JPEG",
        qty: 1,
        size: "L"
      }
    ]));
  }

  function renderOrderSummary() {
    const cart = JSON.parse(localStorage.getItem("cartData")) || [];
    const summary = document.getElementById("orderSummary");
    summary.innerHTML = "";
    let subtotal = 0;

    if (cart.length === 0) {
      summary.innerHTML = "<p>Your cart is empty.</p>";
      document.getElementById("payment-total").innerHTML = "<strong>Total: $0.00</strong>";
      return;
    }

    cart.forEach(item => {
      subtotal += item.price * item.qty;
      summary.innerHTML += `
        <div class="item" style="display:flex; align-items:center; margin-bottom:10px;">
          <img src="${item.image}" alt="${item.name}" style="width:80px; height:auto; object-fit:contain; margin-right:10px;"/>
          <div style="flex:1;">
            <div><strong>${item.name}</strong> (Size: ${item.size || '-'}) × ${item.qty}</div>
            <div>$${(item.price * item.qty).toFixed(2)}</div>
          </div>
        </div>`;
    });

    const discountAmount = subtotal * discountRate;
    const total = subtotal - discountAmount + deliveryFee;

    summary.innerHTML += `
      <div class="summary" style="border-top:1px solid #ccc; padding-top:10px;">
        <div>Subtotal <span style="float:right;">$${subtotal.toFixed(2)}</span></div>
        ${discountRate > 0 ? `<div>Discount <span style="float:right;">-$${discountAmount.toFixed(2)}</span></div>` : ''}
        <div>Shipping <span style="float:right;">$${deliveryFee.toFixed(2)}</span></div>
        <div class="total" style="font-weight:bold; margin-top:5px;">Total <span style="float:right;">$${total.toFixed(2)}</span></div>
      </div>
      <div style="margin-top: 15px;">
        <input type="text" id="promoCodeInput" class="discount-code" placeholder="Enter promo code" />
        <button class="apply-btn" id="applyPromoBtn">Apply Promo</button>
      </div>`;

    document.getElementById("payment-total").innerHTML = `<strong>Total: $${total.toFixed(2)}</strong>`;

    document.getElementById("applyPromoBtn").addEventListener("click", function () {
      const code = document.getElementById("promoCodeInput").value.trim();
      if (code === "subtle") {
        discountRate = 0.10;
        alert("Promo applied! 10% discount.");
      } else {
        discountRate = 0;
        alert("Invalid promo code.");
      }
      renderOrderSummary();
    });
  }

  document.getElementById("regionSelect").addEventListener("change", function () {
    document.getElementById("provinceInputContainer").style.display = this.value === "pr" ? "block" : "none";
  });

  document.getElementById("payNow").addEventListener("click", function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const firstName = document.getElementById("firstName").value.trim();
    const lastName = document.getElementById("lastName").value.trim();
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');

    if (!email || !firstName || !lastName) {
      alert("Please fill in all required fields.");
      return;
    }

    if (!paymentMethod) {
      alert("Please select a payment method.");
      return;
    }

    const cart = JSON.parse(localStorage.getItem("cartData")) || [];
    if (cart.length === 0) {
      alert("Your cart is empty.");
      return;
    }

    let subtotal = 0;
    const itemsText = cart.map(item => {
      subtotal += item.price * item.qty;
      return `Item: ${item.name}, Qty: ${item.qty}, Size: ${item.size || '-'}, Total: $${(item.price * item.qty).toFixed(2)}`;
    }).join('\n');

    const discountAmount = subtotal * discountRate;
    const total = (subtotal - discountAmount + deliveryFee).toFixed(2);

    const form = document.createElement("form");
    form.method = "POST";
    form.style.display = "none";

   const fields = {
  name: firstName + " " + lastName,
  email: email,
  instagram: document.getElementById("instagramInput").value.trim(),
  phone: document.getElementById("phone").value.trim(),
  address: document.getElementById("address").value.trim(),
  region: document.getElementById("regionSelect").value === "pr" ? "Provinces" : "Phnom Penh",
  province: document.getElementById("provinceInput").value.trim(),
  payment: document.querySelector('input[name="paymentMethod"]:checked').value,
  order: `${itemsText}\nSubtotal: $${subtotal.toFixed(2)}\n${discountRate > 0 ? "Discount: -$" + discountAmount.toFixed(2) + "\n" : ""}Shipping: $${deliveryFee.toFixed(2)}\nTotal: $${total}`
};


    for (const key in fields) {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = key;
      input.value = fields[key];
      form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
  });

  renderOrderSummary();
});
</script>

</body>
</html>
