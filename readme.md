<p align="center"><a href="https://aspireapp.com" target="_blank"><img src="https://global-uploads.webflow.com/5ed5b60be1889f546024ada0/5ed8a32c8e1f40c8d24bc32b_Aspire%20Logo%402x.png" width="128" alt="digital banking singapore" class="navbar-logo"></a></p>

## About

This is project is about only to provide the Rest API for demonstrates the small Loan Management System. Which allows uses to apply for a loan. One user will apply the loan, admin will approve the loan and weekly scheduled payment will be created and user needs to pay by weekly along with the interest rate.

## Out of Scope
- This application is not managing the late payments.
- The interest rate has been calculated monthly.
- The integration of a payment gateway.
- The different types of notifications to notify the platform users. 
- User Can see the list the loan has taken.


## Installation

- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan migrate`
- Run `php artisan passport:install`
- Run `php artisan db:seed`


## How to Use

1. Hit `Register Uer` **Request** to register a new user and with the user login API that will return the `token` for each user.

2. Hit `Create Loan` **Request** to create a new loan for that authenticated user along with the Bearer token. By default the Loan Status will be **pending**.

3. Hit the `Admin login` **Request** and get the authorization token, from the responce.

4. Hit `Get all Pending` **Request** and get the pending loan for each user. from this API you will get the all the pending loan with loan Id alongwith the User information.

5. Hit `Loan Approval` **Request**  on this request the user loan will be created and status will be change from **pending** to **approved**. and scheduled payment will be created as per the loan tenure.

6. Hit `Pay Scheduled Repayment`  **Request** to pay the scheduled repayment amount which is greather than OR equal to the  scheduled amount. one the all payment has been made, loan status will be converted to **Paid**


## Postman API

- [Postman Collection](https://www.getpostman.com/collections/9a8f88ab650b8d68d4a2)


## Third-party Packages Used
- [Laravel Passport](https://laravel.com/docs/passport)


## Test Case

- Implemented the feature test case as per the requirement.
- This demo dose not comtain the Unit Test case, as there is no any standalone code to test the same.

