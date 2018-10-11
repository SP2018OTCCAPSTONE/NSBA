<?php

// Dependencies/Whoops/Read .env
require_once('../includes/init.php');

// Connect to the database
require_once('../includes/db.php');

// get order ID from anchor click
$order_ID = filter_input(INPUT_GET, 'order_ID', FILTER_VALIDATE_INT);
if ($order_ID == NULL || $order_ID == FALSE) {
    $order_ID = 1;
}

// This page
$current = "orderDetails";
$title = "Guitars- order details";

// Get all the details of one order from 3 tables
$details = getMany("SELECT * FROM `orders`
                
                JOIN `customers` ON `orders`.`customerID` = `customers`.`customerID`
                JOIN `orderItems` ON `orders`.`orderID` = `orderItems`.`orderID`
                JOIN `products` ON `orderItems`.`productID` = `products`.`productID`
                JOIN `categories` ON `products`.`categoryID` = `categories`.`categoryID`
                JOIN `addresses` ON `customers`.`billingAddressID` = `addresses`.`addressID`
                -- JOIN
                -- (
                --     SELECT SUM( `orderItems`.`quantity` * (`products`.`listPrice` - (`products`.`listPrice` * `products`.`discountPercent` / 100)) AS subtotal
                --     FROM `orderItems`
                --     JOIN `products` ON  `orderItems`.`orderID` = `product`.`orderID`
                --     -- WHERE `orders`.`orderID` = $order_ID
                --     -- GROUP BY `orders`.`orderID`
                -- ) sub
                WHERE `orders`.`orderID` = $order_ID", [], $conn);


                
// $sub = json_decode(json_encode($order_details),true);

//Variables for calculation purposes 
$tax_amount = $details[0]['taxAmount'];
$ship_date1 = $details[0]['shipDate'];
$ship_amount = $details[0]['shipAmount'];

// var_dump($details);
?>

                    <?php include("../includes/header_nav.php") ?>
                    <!-- Unique page content here -->
            <div class="row align-items-center" style="height: 100%;">
                <div class="col-sm"></div>
                <div class="col-sm-9">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header text-center font-weight-bold text-white bg-info mb-3">
                        <h2 class="text-center">Order Details</h2>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-6">Order Date:</dt>
                                <dd class="col-6"><?= $details[0]['orderDate'] ?></dd>

                                <dt class="col-6">Ship Date</dt>
                                <dd class="col-6"><?= $ship_date1 == NULL ? "<a href='unshipped_orders.php' class='text-warning'>Unshipped</a>" : $ship_date1 ?></dd>

                                <dt class="col-6">CC Used:</dt>
                                <dd class="col-6"> Card# <?= $details[0]['cardNumber']." Card Type: ".$details[0]['cardType'] ?></dd>

                                <dt class="col-6">Billing Address:</dt>
                                <dd class="col-6"><?= $details[0]['firstName']." ".$details[0]['lastName']."<br>".$details[0]['line1']." ".$details[0]['city']." ".$details[0]['state']." ".$details[0]['zipCode'] ?></dd>

                                <hr>

                                <?php foreach($details as $i => $value) : ?>
                                <?php 
                                    $list = $details[$i]['listPrice'];
                                    $qty = $details[$i]['quantity'];
                                    $discount = $details[$i]['discountPercent'];
                                    static $subtotal;
                                    $price = $qty * ($list - ($list * $discount / 100));
                                    $subtotal += $price;
                                ?>
                                <dt class="col-6 text-right">Order Item: </dt>
                                <dd class="col-6"><a href="product_details.php?product_ID=<?= $details[$i]['productID'];?>"><?= $details[$i]['productName'] ?></a></dd>

                                <dt class="col-6">Category:</dt>
                                <dd class="col-6"><?= $details[$i]['categoryName'] ?></dd>
                                
                                <dt class="col-6">Item Quantity:</dt>
                                <dd class="col-6"><?= $qty ?></dd>
                                
                                <dt class="col-6">Item Price:</dt>
                                <dd class="col-6"><?= "$".number_format($list, 2) ?></dd>

                                <dt class="col-6">Discount Price (<?= number_format($discount)."% off" ?>):</dt>
                                <dd class="col-6"><?= "$".number_format($price, 2) ?></dd>

                                <hr>
                                <?php endforeach; ?>

                                <?php
                                    global $subtotal;
                                    $total = $subtotal + $tax_amount + $ship_amount;
                                ?>                              
                                <dt class="col-6">Order Tax Amount:</dt>
                                <dd class="col-6"><?= "$".number_format($tax_amount, 2) ?></dd>

                                <dt class="col-6">Order Shipping Amount:</dt>
                                <dd class="col-6"><?= "$".number_format($ship_amount, 2) ?></dd>

                                <dt class="col-6">Total Price:</dt>
                                <dd class="col-6"><?= "$".number_format($total, 2) ?></dd>
                            </dl> 
                            <div class="text-center">
                                <a href="javascript:history.back()" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Previous Page</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
                    <?php include("../includes/footer.html") ?>


               