# Subscription Payment

This package is created using laravel 8, livewire, mysql and tailwind css. We have used cron job and task scheduling to handle 3rd party payment gateways(Stripe and razorpay).

## Tech
- [Laravel](https://laravel.com/docs/8.x/installation).
- [Livewire](https://laravel-livewire.com//).
- [Tailwind CSS](https://tailwindcss.com/).
- Multiple back-ends for [session](https://laravel.com/docs/8.x/session) and [cache](https://laravel.com/docs/8.x/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/8.x/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/8.x/migrations).
- [Robust background Task Scheduling](https://laravel.com/docs/8.x/scheduling).
- [Stripe](https://stripe.com/docs/api).
- [Razorpay](https://razorpay.com/docs/api).

## Installation

### Run these commands

```sh
cd poc-subscription-payment
composer install
npm install
npm run dev
php artisan key:generate
```

### For production environments 

set your database and file storage credentials in your env...
```sh
FILESYSTEM_DRIVER=public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

set you payment credentials in your env...
```sh
RAZORPAY_KEY=
RAZORPAY_SECRET=

STRIPE_KEY=
STRIPE_SECRET=
```

### Run these commands
```sh
php artisan config:cache
php artisan migrate --seed
```

## [Database Architecture](https://app.quickdatabasediagrams.com/#/d/0BycSy)

## Default Admin Credentials
- These credentials are stored in UserSeeder.
- Email: admin@gmail.com 
- Password: password

## Modules

### Roles
- There will be 2 roles - Admin, Customer.
- Admins will be able to manage products, orders and subscriptions.
- Customers will be able to see all products and buy products based on a order mode or subscription payment mode.
- The authentication method will be the by default method of laravel.
- Users will include additional fields of billing address and stripe customer id.

### Products
- Products will include name, image, description, type(single or subscription), [stripe product id](https://stripe.com/docs/api/products), basic amount and country code.
- Products have 2 subscription plans - Basic(default) and Pro.
- Admin needs to add amount in every currency that they would like to use for a product.
- Every time admin updates the amounts of the old products their subscription plans will be updated respectively but we have not implemented the update functionality for products.
- Customer will select a dropdown of countries along with their price and based on that payment options will be shown.

### Prices
- Every product has multiple prices.
- These prices will be added in razorpay as [plans](https://razorpay.com/docs/api/payments/subscriptions#create-a-plan) for subscription and prices as [prices](https://stripe.com/docs/api/prices) in stripe.
- Prices will include type which can be either Basic(for single payments and subscriptions) and Pro.

### Subscriptions
- Every subscription will be linked to a product and user.
- Every subscription has plan type
- We have used 2 subscription methods [Stripe](https://stripe.com/docs/api) and [Razorpay](https://razorpay.com/docs/api).
	- Stripe
		- There will be product and multiple prices of that product.
		- Every subscription will be linked to a price.
		- [Supported Currencies](https://stripe.com/docs/currencies).
		- In case user upgrades the subscription plan if all the invoices are paid only then user can upgrade the subscription. We will remove the pevious price from that subscription and link the new price.
		- In case user cancels the subscription if all the invoices are paid only then user can cancel the subscription.
		- Status: Created(default), Active, Completed, Cancelled.
	- Razorpay
		- There will be plans based on price and product.
		- Every subscription will be linked to a particular plan.
		- [Supported Currencies](https://razorpay.com/docs/build/browser/assets/images/international-currency-list.xlsx) however you need to complete your KYC for using international currencies.
		- In case user upgrades the subscription if all the charges have been collected till then and the subscription has status active only then plan we will update the plan id for that subscription.
		- If you want to prvide option to upgrade then the upi option needs to be stoped from razorpay account.
		- In case user cancels the subscription if all the charges have been collected till then and the subscription has status active only then user can cancel the subscription.
		- Status: Created(default), Active, Completed, Cancelled, Hold.
		- The subscription moves to hold if razorpay fails to capture a charge after the attempts have expired. In this case the user will have to add another payment method.

### Orders
- Every order will be linked to a product and user.
- Status: Created(default), Completed, Cancelled.

### Invoices
- Every invoice will be linked to a subscription or order.
- Subscriptions
	- Stripe
		- The invoices will be created using cron job every hour.
		- The invoice status will update after every 5 minutes.
		- Every invoice has stripe invoice id provided by the subscription.
	- Razorpay
		- Razorpay does not issues invoices for subscriptions.
- Orders
	- Stripe
		- The invoice will be created on every order.
		- The invoice status will update after every 5 minutes.
		- Every invoice has stripe invoice id provided by the order.
	- Razorpay
		- The invoice will be created on every order.
		- The invoice status will update after every 5 minutes.
		- Every invoice has razorpay invoice id provided by the order.