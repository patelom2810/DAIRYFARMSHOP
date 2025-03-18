# Dairy Farm Shop

## Project Overview
Dairy Farm Shop is a web-based management system designed for dairy product sales and inventory tracking. It allows administrators to manage products, generate bills, track stock, and view sales reports efficiently.

## Tech Stack
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL (Managed via phpMyAdmin)

## Key Features
✅ **User Authentication** – Secure login for admins 👤🔑  
✅ **Product Management** – Add, update, and delete dairy products easily 🛒📦  
✅ **Billing System** – Generate invoices quickly and accurately 💵🧾  
✅ **Stock Management** – Keep track of available stock in real-time 📊📉  
✅ **Sales Report Generation** – View total revenue and transactions in one place 📈💰  
✅ **Receipt Printing** – Generate and print receipts for customers instantly 🖨️  

## Project Folder Structure
```
DairyFarmShop/
│── billing/           # Billing system logic
│── cow/               # Cow-related information
│── dairy_farm/        # Core files for dairy farm shop
│── dashboard/         # Admin dashboard
│── db/                # Database connection files
│── file/              # Miscellaneous file storage
│── get_product/       # Fetch product details
│── login/             # User authentication (Admin Login)
│── logout/            # Logout system
│── product_manage/    # Add, update, delete products
│── receipt/           # Generate receipts
│── sales_report/      # Generate sales reports
│── style/             # CSS files for styling
│── images/            # Image storage (Product images, logo, etc.)
│    ├── 1.jpg
│    ├── 2.jpg
│    ├── 3.jpg
│    ├── 4.jpg
│    ├── 5.jpg
```

## Database Structure
**Tables:**
- `users` (Stores admin login details)
- `products` (Stores product information)
- `billing` (Stores bill records, auto-generates `bill_id`)
- `sales_report` (Stores sales transaction history)
- `stock` (Tracks product stock levels)

## Installation & Setup
1. Clone the repository or copy project files to your local server.
2. Import the `dairy_farm.sql` database into phpMyAdmin.
3. Configure database connection in `db/config.php`.
4. Run the project on `localhost` using XAMPP or any PHP server.
5. Log in using admin credentials (`admin / password`).

## Future Enhancements
- Customer login and order tracking.
- Payment gateway integration.
- Detailed analytics dashboard.

## License
This project is open-source and free to use for learning purposes.


