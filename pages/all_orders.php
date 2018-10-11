<?php

// Dependencies/Whoops/Read .env
require_once('../includes/init.php');

// Connect to the database 
require_once('../includes/db.php');

// This page
$current = "orders";
$title = "Guitars- all orders";

// Queries for pertinent tables
$orders = getMany("SELECT * FROM orders", [], $conn);
$customers = getMany("SELECT * FROM customers", [], $conn);

// For date/time formatting
use Carbon\Carbon;

// // get all products
// $queryAllOrders = 'SELECT * FROM orders ORDER BY orderID';
// $statement1 = $conn->prepare($queryAllOrders);
// $statement1->execute();
// $orders = $statement1->fetchAll();
// $statement1->closeCursor();

//var_dump($orders);
?>


                    <?php include("../includes/header_nav.php") ?>

                    <!-- Unique page content here -->
                    <div class="row align-items-center" style="height: 100%;">
                    <div class="col-sm"></div>
                    <div class="col-sm-9">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-header text-center font-weight-bold text-white bg-info mb-3">
                                <h2>All Orders</h2>
                            </div>
                            <div class="card-body">
                                <div>
                                    <h5 class="text-center">All Orders</h5>
                                    <table class="table table-striped table-dark">
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Customer Email</th>
                                            <th scope="col" class="text-right">Order Date</th>
                                        </tr>
                                        <?php foreach ($orders as $order) : ?>
                                        <?php $currentOrder = $order['customerID']; ?> 
                                        <?php $currentCustomer = getOne("SELECT * FROM  customers WHERE customerID = $currentOrder", [], $conn); ?>
                                <!---->
                                        <tr>
                                            <td><?php echo $order['orderID']; ?> <a class="btn btn-primary" href="order_details.php?order_ID=<?= $order['orderID'];?>">Details <i class="fas fa-chevron-right"></i></a></td>
                                            <td><?php echo $currentCustomer['firstName']." ".$currentCustomer['lastName']; ?></td>
                                            <td><?php echo $currentCustomer['emailAddress']; ?></td>
                                            <td class="text-right"><?php echo Carbon::parse($order['orderDate'])->format("M jS 'y"); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                <!---->      
                                    </table>       
                                </div>      
                            </div>
                        </div>
                    </div>
                    <div class="col-sm"></div>
            </div>

                    <?php include("../includes/footer.html") ?>
            