# PHP Task: Shopping Cart Checkout System

## Overview
Create a simple checkout system with PayPal payment gateway integration using vanilla PHP (no frameworks). The system should process a predefined cart of products through PayPal checkout.

### Technical Requirements
- PHP 8.1 or higher
- No PHP frameworks allowed (vanilla PHP only)
- Object-Oriented Programming (OOP) principles
- PSR-4 autoloading
- Composer for dependency management
- Clean, well-documented code following PSR coding standards

### Get started
- Clone the repository - `git clone git@github.com:ahamed/ollyo-php-developer-task.git`
- Run `cd ollyo-php-developer-task`
- Run `composer install`
- **Note**: setup instructions are as is since not set up steps modified

### Api Documentation

- Please find the relevant API Endpoint documentation [here](https://www.github.com)

### Task Status
1. Complete the View System ✅
   - Navigate to `/helper.php` and implement the view() function ✅
   - Function should accept view name and data array parameters ✅
   - Handle loading view files from /src/Views directory ✅
   - Extract data array to make variables available in views ✅

2. Create Payment Result Views ✅
   - Create `success.php` view in /src/Views showing:
     - Order confirmation message ✅
     - Order details summary 
     - Transaction ID 
     - Thank you message ✅
   - Create `failure.php` view in /src/Views showing:
     - Error message ✅
     - Reason for failure ✅
     - Try again button ✅
     - Contact support information

3. Complete the Checkout View ✅
   - Calculate subtotal from products array ✅
   - Add shipping cost calculation ✅
   - Validate all required customer fields ✅
   - Format currency values properly ✅
   - Add input validation and sanitization ✅

4. Implement ❌PayPal ✔️Stripe Integration ✅
   - Add ✔️Stripe SDK to project ✅
   - Configure ✔️Stripe credentials ✅
   - Create payment intent in `/index.php` checkout route ✅
   - Handle Stripe callbacks and ❌webhooks ✅
   - Process successful and failed payments ✅

5. Build Payment Controllers (optional)
   - Create PaymentController class
   - Add methods for:
     - Creating payment
     - Processing payment
     - Handling success/failure
     - Verifying transaction
   - Implement proper error handling

6. Add Payment Verification ✅
   - Verify payment amount matches cart total ✅
   - Check payment status from Stripe ✅
   - Validate transaction ID ✅

7. Configure Additional Routes
   - Add route for payment success callback ✅
   - Add route for payment failure callback ✅
   - Add route for payment verification ❌
   - Add route for order confirmation ✅

8. Bonus: Recurring Payments
   - Implement subscription billing
   - Handle recurring payment schedules
   - Process automatic rebilling
   - Manage subscription status

9. Project Completion
   - Push completed code to GitHub ✅
   - Include setup instructions in README ✅
   - Document API endpoints ✅
   - Share repository link ✅
