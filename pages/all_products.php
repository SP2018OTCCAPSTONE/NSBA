<?php

// Dependencies/Whoops/Read .env
require_once('../includes/init.php');

// Connect to the database
require_once('../includes/db.php');

/* // Troubleshooting code to determine 32 0r 64 bit php version
switch(PHP_INT_SIZE) {
    case 4:
        echo '32-bit version of PHP';
        break;
    case 8:
        echo '64-bit version of PHP';
        break;
    default:
        echo 'PHP_INT_SIZE is ' . PHP_INT_SIZE;
} */

// This page
$current = "products";
$title = "Guitars- all products";

// Queries for pertinent tables
$categories = getMany("SELECT * FROM categories", [], $conn);

?>

                    
                    <?php include("../includes/header_nav.php") ?>

                    <!--***Unique page content here*************************-->
            <div class="row align-items-center" style="height: 100%;">
                    <div class="col-sm"></div>
                    <div class="col-sm-9">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header text-center font-weight-bold text-white bg-info mb-3">
                                <h2>All Products</h2>
                            </div>
                            <div class="card-body">
                                <div>
                                <?php foreach ($categories as $category) : ?>
                                <!---->
                                    <h5 class="text-center"><?php echo $category['categoryName']; ?></h5>
                                    <?php $currentCategory = $category['categoryID']; ?> 
                                    <?php $currentID = getMany("SELECT * FROM  products WHERE categoryID = $currentCategory", [], $conn); ?>
                                <!---->
                                    <table class="table table-striped table-dark">
                                        <tr>
                                            <th scope="col">Product Code</th>
                                            <th scope="col" class="text-right">Price</th>
                                        </tr>
                                        <?php foreach ($currentID as $product) : ?>
                                <!---->
                                        <tr>
                                            <td><a href="product_details.php?product_ID=<?= $product['productID'];?>"><?php echo $product['productName']; ?></a></td>
                                            <td class="text-right">$<?php echo $product['listPrice']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                <!---->      
                                    </table>       
                                <?php endforeach; ?>
                                <!---->
                                </div>      
                            </div>
                        </div>
                    </div>
                    <div class="col-sm"></div>
            </div>
                    
                    <?php include("../includes/footer.html") ?>