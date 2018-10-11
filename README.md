# Midterm Project - My Guitar Shop

Using the data available in `my_guitar_shop.sql`, create a website that does the items outlined below. You may use any front-end technology you'd like, including (but not limited to) Bootstrap and/or Vue.js.

## Project Requirements

1. Create An Order Section
    - Display a listing of all orders. Include customers names, email address & order date
    - Click on an order and get detailed info about that order:
        - Order Date
        - Ship Date
        - Credit Card Used
        - Billing Address
        - Products Ordered
        - Item Price
        - Order Discount Amt
        - Order Tax Amt
        - Order Shipping Amt
        - Order Total
    - Create a listing of orders not yet shipped
    - Click on that order and get order details
2. Create a Products page
    - Create a page that lists all products Organized by Category Type with price
    - Click on a product and see product name, description
3. The site should look professional

## Things to Consider

- Read the instructions in `includes/db.php` before connecting to your database!
- Do not remove the `require('./includes/init.php')` from any of your pages. You may clean up the comments if you'd like.

## Re-Submission

If you find yourself unhappy with your grade on this project, I'll be allowing you to re-submit this project no later than April 9th, 2018 at 8:00am for re-evaluation. If you chose to re-submit the project, it will have slightly different requirements, most significantly that your re-submission must be created in Laravel.

More details will be provided as we progress through Laravel after midterms.

## License

This exam is published under the MIT License.

Dependency Licenses:

```
Name              Version  License
filp/whoops       2.1.14   MIT
psr/log           1.0.2    MIT
vlucas/phpdotenv  v2.4.0   BSD-3-Clause-Attribution
```