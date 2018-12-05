
# Capstone Project - NSBA Membership Management Application

1. This project/repository should be in Windows(C:) > xampp > htdocs

2. To run and view this application you must first install composer

3. Create a .env file using the .env.example file included in the root directory of this project as an example. The file should be named .env

4. In the 'View' menu in VS, select 'Terminal' and type the following command into the terminal: composer install

5. Bring up Xampp control panel and start Apache and MySql 

6. Open up PhpMyAdmin and create a new empty database

7. Select the empty database and select import from the menu bar 

8. Choose the database file and import it into empty database

9. Type localhost/NSBA in the address bar and the project should launch

10. To create an admin account you will need 2 gmail accounts with which you have enabled less secure apps to login. This google help page has a link to follow: https://support.google.com/accounts/answer/6010255?hl=en



### NSBA Project TODO:

- [ ] Develop invoicing functionality
- [ ] Further develop reports functionality
- [ ] Implement Js form validation to minimize page refreshes
- [x] Finish implementing Corporate 2 & 3 membership DB insert process
- [ ] Implement export to Excel/Csv and print functionality for reports
- [ ] Further develop the mailer (PhpMailer) functionality
- [ ] Implement image file upload/processing
- [ ] Implement the regular user edit profile functionality 
- [ ] Implement regular user-side renewals/payments functionality
- [ ] Implement PayPal portal
- [ ] Launch application on a production server



## License

This project is published under the MIT License.

Dependency Licenses:

```
Name              Version  License
filp/whoops       2.1.14   MIT
psr/log           1.0.2    MIT
vlucas/phpdotenv  v2.4.0   BSD-3-Clause-Attribution
```