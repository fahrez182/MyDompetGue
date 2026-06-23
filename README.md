# MyDompetGue

MyDompetGue is a personal finance management application designed to help users track their expenses and income efficiently.

## Features

- **Expense and Income Tracking**: Easily record and categorize all your financial transactions.
- **Semi-Realtime Currency Exchange Rates**: Stay updated with semi-realtime currency exchange rates, allowing for accurate tracking of international transactions.
- **User Roles**:
    - **Basic User**: Access core expense and income tracking functionalities.
    - **Premium User**: Enjoy advanced features and benefits (details to be defined).

## To-Do List Features

### Fase 1: Core Functionality (Basic User)

1.  [x] **User Authentication & Authorization**:
    *   User Registration (Basic User)
    *   Login & Logout
    *   Middleware to protect authenticated routes.
2.  [x] **Dashboard**:
    *   Financial summary overview (total income, expenses, balance).
    *   Simple charts (optional, can be added later).
3.  **Transaction Management**:
    * [x] Add Transactions (Income/Expense).
    * [x] Edit Transactions.
    * [x] Delete Transactions.
    * [x] View Transaction List (with date and category filters).
4.  **Transaction Categories**:
    * [x] Create, Edit, Delete Categories (e.g., Food, Transportation, Salary).

### Fase 2: Currency Exchange Rate Features

1.  [x] **Exchange Rate API Integration**:
    *   Select and integrate a currency exchange rate API (e.g., ExchangeRate-API, Open Exchange Rates).
    *   Store exchange rates in the database periodically (semi-realtime).
2.  [x] **Currency Conversion**:
    *   Allow users to select transaction currency.
    *   Display transaction values in both the user's base currency and the transaction currency.

### Fase 3: User Roles (Premium Features)

1.  **Role Implementation**:
    *   Add a `role` column to the `users` table (or a separate roles table).
    *   Mechanism to change user roles (e.g., from Basic to Premium).
2.  **Premium Features (Examples)**:
    *   **Advanced Reporting**: More detailed financial reports with complex filters and visualizations.
    *   **Budgeting**: Features to create and track monthly/annual budgets.
    *   **Multiple Wallets/Accounts**: Manage more than one wallet/account.

### Fase 4: Refinement & Optimization

1.  **Input Validation**: Ensure all user inputs are valid.
2.  **Notification Messages**: Provide feedback to users after actions (success/failure).
3.  **Pagination**: For long transaction lists.
4.  **Testing**: Unit and Feature tests.
5.  **Deployment**: Prepare the application for production.

## Routing Strategy

Laravel uses `routes/web.php` for web-based routes (returning HTML) and `routes/api.php` for API routes (returning JSON). For now, we will focus on `web.php`.

### Basic Routing Concepts:

*   **Route Definition**: Connects a URL to an action in a Controller or a Closure.
    ```php
    // routes/web.php
    Route::get('/welcome', function () {
        return view('welcome');
    });

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
    ```
*   **Named Routes**: Assigns a name to a route for easy referencing in views or redirects.
    ```php
    Route::get('/profile', [UserController::class, 'show'])->name('profile');
    // In a view: <a href="{{ route('profile') }}">Profile</a>
    ```
*   **Route Parameters**: Captures parts of the URL as parameters.
    ```php
    Route::get('/users/{id}', [UserController::class, 'show']);
    ```
*   **Route Groups**: Groups routes with common middleware, prefixes, or namespaces.
    ```php
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('transactions', TransactionController::class); // Example resource route
    });
    ```
*   **Resource Routes**: For CRUD (Create, Read, Update, Delete) operations on a single resource (e.g., `transactions`). This automatically creates routes for `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`.
    ```php
    Route::resource('transactions', TransactionController::class);
    ```

### Initial Routing Plan:

1.  **Authentication**:
    *   `GET /register` -> Display registration form.
    *   `POST /register` -> Process registration.
    *   `GET /login` -> Display login form.
    *   `POST /login` -> Process login.
    *   `POST /logout` -> Process logout.
    *   Use `guest` middleware for login/register routes and `auth` middleware for authenticated routes.

2.  **Dashboard**:
    *   `GET /dashboard` -> Display the main dashboard. (Requires `auth` middleware)

3.  **Transactions**:
    *   `GET /transactions` -> Display transaction list.
    *   `GET /transactions/create` -> Display add transaction form.
    *   `POST /transactions` -> Store new transaction.
    *   `GET /transactions/{transaction}` -> Display transaction details.
    *   `GET /transactions/{transaction}/edit` -> Display edit transaction form.
    *   `PUT/PATCH /transactions/{transaction}` -> Update transaction.
    *   `DELETE /transactions/{transaction}` -> Delete transaction.
    *   Can use `Route::resource('transactions', TransactionController::class);` for this.
