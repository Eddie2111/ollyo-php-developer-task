# API Endpoint Documentation

This API handles a simple checkout process using Stripe as the payment gateway. Below is the detailed documentation of the endpoints and their functionality.

---

## Base URL

The base URL for this API is dynamically determined by the `BASE_URL` constant, which resolves to the root of your application.

---

## Endpoints

### 1. **GET `/`**
- **Description**: Renders the main application view.
- **Response**:
  - Returns the `app` view with no additional data.

---

### 2. **GET `/checkout`**
- **Description**: Displays the checkout form with product details, shipping information, and order summary.
- **Response**:
  - Returns the `checkout` view with the following data:
    ```php
    [
        'products' => [
            [
                'name' => 'Minimalist Leather Backpack',
                'image' => '<BASE_URL>/resources/images/backpack.webp',
                'qty' => 1,
                'price' => 120,
            ],
            [
                'name' => 'Wireless Noise-Canceling Headphones',
                'image' => '<BASE_URL>/resources/images/headphone.jpg',
                'qty' => 1,
                'price' => 250,
            ],
            // Additional products...
        ],
        'shipping_cost' => 10,
        'address' => [
            'name' => 'Sherlock Holmes',
            'email' => 'sherlock@example.com',
            'address' => '221B Baker Street, London, England',
            'city' => 'London',
            'post_code' => 'NW16XE',
        ],
    ]
    ```

---

### 3. **POST `/checkout`**
- **Description**: Processes the checkout form submission, validates user input, and initiates a payment request via Stripe.
- **Request Body**:
  - Form data containing the following fields:
    - `name` (string): Full name of the customer.
    - `email` (string): Email address of the customer.
    - `address` (string): Shipping address.
    - `city` (string): City of the customer.
    - `post_code` (string): Postal code of the customer.
- **Validation**:
  - The following validations are performed on the input:
    - `name`: Must not be empty.
    - `email`: Must be a valid email format and must not contain spaces.
    - `address`: Must not be empty.
    - `city`: Must not be empty.
    - `post_code`: Must match the format `^[a-zA-Z0-9\s\-]+$`.
- **Stripe Payment Processing**:
  - The total amount is calculated in cents (`$total * 100`) based on the cart subtotal and shipping cost.
  - A `PaymentIntent` is created using the Stripe API with the following parameters:
    - `amount`: Total amount in cents.
    - `currency`: `usd`.
    - `payment_method_types`: `['card']`.
    - `description`: "Order from [Customer Name]".
    - `receipt_email`: Customer's email address.
    - `metadata`: Includes customer details (`name`, `email`, `address`, `city`, `post_code`).
  - Additional checks:
    - Verifies that the payment amount matches the cart total.
    - Confirms that the payment status is `succeeded`.
    - Validates the transaction ID (`paymentIntent->id`).
- **Responses**:
  - **Success**:
    - Redirects to the `/success` endpoint with the `paymentIntent` object passed to the `success` view.
  - **Failure**:
    - Redirects to the `/failure` endpoint with an error message if:
      - Validation fails.
      - Stripe API throws an error.
      - Custom errors occur (e.g., amount mismatch, invalid transaction ID).

---

### 4. **GET `/success`**
- **Description**: Displays a success page after a successful payment.
- **Response**:
  - Returns the `success` view with the following data:
    ```php
    [
        'paymentIntent' => $paymentIntent // Stripe PaymentIntent object
    ]
    ```

---

### 5. **GET `/failure`**
- **Description**: Displays an error page if the payment fails or an error occurs during processing.
- **Response**:
  - Returns the `failure` view with the following data:
    ```php
    [
        'error' => $errorMessage // Error message string
    ]
    ```

---

## Error Handling

- **Validation Errors**:
  - If any required field is missing or invalid, the API returns the `checkout` view with error messages displayed next to the respective fields.
- **Stripe API Errors**:
  - If the Stripe API encounters an issue (e.g., invalid API key, insufficient funds), the API redirects to the `/failure` endpoint with the error message.
- **Custom Errors**:
  - Custom errors (e.g., payment amount mismatch, invalid transaction ID) are caught and handled similarly by redirecting to the `/failure` endpoint.

---

## Example Request and Response

### POST `/checkout`
#### Request Body:
```json
{
    "name": "John Doe",
    "email": "johndoe@example.com",
    "address": "123 Main St",
    "city": "New York",
    "post_code": "10001"
}
```

#### Success Response:
- Redirects to `/success` with the following data:
```php
[
    'paymentIntent' => [
        'id' => 'pi_123456789',
        'amount' => 37000, // Total amount in cents
        'status' => 'succeeded',
        'metadata' => [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'address' => '123 Main St',
            'city' => 'New York',
            'post_code' => '10001'
        ]
    ]
]
```

#### Failure Response:
- Redirects to `/failure` with the following data:
```php
[
    'error' => 'Payment failed. Status: requires_payment_method'
]
```

## References

- [Stripe API Documentation](https://stripe.com/docs/api)
- [Stripe PHP Library](https://github.com/stripe/stripe-php)

---