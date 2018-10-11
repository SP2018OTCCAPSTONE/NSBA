<?php

// Dependencies/Whoops/Read .env
require_once('../includes/init.php');

// Connect to the database
require_once('../includes/db.php');

// get product ID from anchor click
$product_ID = filter_input(INPUT_GET, 'product_ID', FILTER_VALIDATE_INT);
if ($product_ID == NULL || $product_ID == FALSE) {
    $product_ID = 1;
}

// This page
$current = "productDetails";
$title = "Guitars- product details";

// Queries for pertinent tables
$details = getMany("SELECT * FROM products
                    JOIN `categories` ON `products`.`categoryID` = `categories`.`categoryID`
                    WHERE `products`.`productID` = $product_ID", [], $conn);

//$details = json_decode(json_encode($product_details),true);

// Variables fo calculations
$list_price = $details[0]['listPrice'];
$list_price_f = "$".number_format($list_price, 2);
$discount_percent = $details[0]['discountPercent'];
$discount_percent_f = number_format($discount_percent, 0)."%";
$discount_price = $list_price - ($list_price * $discount_percent / 100);
$discount_price_f = "$".number_format($discount_price, 2);
//var_dump($details);
?>

                    
                    <?php include("../includes/header_nav.php") ?>

                    <!--***Unique page content here*************************-->
            <div class="row align-items-center" style="height: 100%;">
                    <div class="col-sm"></div>
                    <div class="col-sm-9">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header text-center font-weight-bold text-white bg-info mb-3">
                                <h2>Product Details</h2>
                            </div>
                            <div class="card-body">
                                <div>
                                    <h5 class="text-center"><?= $details[0]['productName']; ?></h5>
                                    <table class="table table-striped table-dark">
                                        <tr>
                                            <th scope="col">Category</th>
                                        </tr>
                                        <tr>
                                            <td><?= $details[0]['categoryName']; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="col">Description</th>
                                        </tr>
                                        <tr>
                                            <?php $description = $details[0]['description'];
                                                // This used to convert a strange slash character to UTF-8 so it can be replaced with regex
                                                // Must require "ext-mbstring": "*" in composer.lock to work on heroku server
                                                $description = mb_convert_encoding($description, 'UTF-8', 'UTF-8');
                                                $description = preg_replace(array('/\?/', '/\*/'), array(', ', '<br>&emsp;*'), $description);                                                
                                            ?>
                                            <td><?= $description; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="col">List Price</th>
                                        </tr>
                                        <tr>
                                            <td><?= $list_price_f; ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="col">Discount Price (<?= $discount_percent_f." off" ?>)</th>
                                        </tr>
                                        <tr>
                                            <td><?= $discount_price_f; ?></td>
                                        </tr>
                                    </table>                                
                                </div>
                                <div class="text-center">
                                    <a href="javascript:history.back()" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Previous Page</a>
                                </div>      
                            </div>
                        </div>
                    </div>
                    <div class="col-sm"></div>
            </div>
                    
                    <?php include("../includes/footer.html") ?>

