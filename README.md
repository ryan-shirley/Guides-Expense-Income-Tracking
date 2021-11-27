# Guides Expense & Income Consolidation

The aim of this project was to organise and keep track of expenses and incoming money within an small organisation.

### Admin Dashboard
![Admin Dashboard](https://raw.githubusercontent.com/ryan-shirley/Guides-Expense-Income-Tracking/master/screenshots/Guides%20Admin%20Dashboard.png)

### Leader Dashbaord
![Leader Dashboard](https://raw.githubusercontent.com/ryan-shirley/Guides-Expense-Income-Tracking/master/screenshots/Guides%20Leader%20Dashboard.png)

### Chatbot
Chatbot for leaders to be able to add payments with a conversational experience

![Chatbot for adding expenses](https://raw.githubusercontent.com/ryan-shirley/Guides-Expense-Income-Tracking/master/screenshots/Chatbot-Screenshot.png)

## How it works
Payments are added by leaders but require approval by an admin. This allows admins to keep track of individual leader expenses and total organisation expenses. Admins are also able to input incoming money.

Graphs are provided for current year totals on a monthly grouping. With the addition of a bank balance value a history of balance is also available.

Both expenses and incoming money can be exported to .csv for accounting purposes.

## Install Laravel & Database Config

In the project directory, you can run:

### `composer install`
Installs laravel packages into project directory.

Create .env file from the .env.example and input correct database information

## Migrate and seed database

### `php artisan migrate --seed`
Creates the database and seeds it with test data.

## Run Project

### `php artisan serve`

Runs the app in the development mode.<br />
Open [http://localhost:8000](http://localhost:8000) to view it in the browser.

### `npm run watch`
Complies any changes to sass files.
