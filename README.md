# TaxSafar - CA Firm Landing Page + Inquiry Management System

A core PHP and MySQL web application for a CA/Tax Consultancy company. It includes a polished public landing page, inquiry form, admin login, dashboard statistics, and inquiry management.

## Project Structure

```text
TaxSafar_Assignment/
|-- admin/
|   |-- dashboard.php
|   |-- login.php
|   |-- logout.php
|   `-- manage_inquiries.php
|-- config/
|   `-- database.php
|-- includes/
|   |-- footer.php
|   `-- header.php
|-- public/
|   |-- css/
|   |   `-- style.css
|   |-- js/
|   |   `-- script.js
|   `-- index.php
|-- sql/
|   `-- database.sql
|-- .gitignore
|-- ADMIN_CREDENTIALS.txt
|-- SETUP_INSTRUCTIONS.md
|-- change_admin_password.php
`-- README.md
```

## Features



### Public Website

- Modern landing page for TaxSafar services
- Image-backed hero section with call-to-action buttons
- Responsive service grid
- Inquiry form with client-side and server-side validation
- Success and error feedback after form submission

### Admin Authentication

- Email and password based admin login
- Password hashing with bcrypt
- Session-based authentication
- Protected admin routes
- Logout support

### Admin Dashboard

- Total inquiry count
- New inquiry count
- Contacted inquiry count
- Closed inquiry count
- Recent inquiries table

### Inquiry Management

- View all submitted inquiries
- Search by name, email, or mobile number
- Filter by inquiry status
- Update inquiry status
- View inquiry messages
- Delete inquiries with confirmation

## Technology Stack

- Backend: Core PHP
- Database: MySQL
- Database Access: PDO prepared statements
- Frontend: HTML5, CSS3, Vanilla JavaScript
- Server: XAMPP or any PHP/MySQL environment

## Setup Instructions

### Prerequisites

- XAMPP or similar PHP/MySQL server
- PHP 7.0 or higher
- MySQL 5.7 or higher
- Web browser

### Installation

1. Place the project folder in:

   ```text
   C:\xampp\htdocs\TaxSafar_Assignment
   ```

2. Start Apache and MySQL from XAMPP.

3. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

4. Import the SQL file:

   ```text
   sql/database.sql
   ```

5. Confirm database settings in:

   ```text
   config/database.php
   ```

   Default values:

   ```text
   Host: localhost
   User: root
   Password: empty
   Database: taxsafar_db
   ```

6. Open the public website:

   ```text
   http://localhost/TaxSafar_Assignment/public/index.php
   ```

7. Open the admin panel:

   ```text
   http://localhost/TaxSafar_Assignment/admin/login.php
   ```

## Default Admin Credentials

```text
Email: admin@taxsafar.com
Password: Admin@123
```

Important: Change the admin password after first login, especially before using this outside a local assignment/demo environment.

## Database Schema

### admins

```sql
id INT PRIMARY KEY AUTO_INCREMENT
name VARCHAR(100) NOT NULL
email VARCHAR(100) UNIQUE NOT NULL
password VARCHAR(255) NOT NULL
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
```

### inquiries

```sql
id INT PRIMARY KEY AUTO_INCREMENT
full_name VARCHAR(100) NOT NULL
email VARCHAR(100) NOT NULL
mobile VARCHAR(15) NOT NULL
city VARCHAR(50) NOT NULL
service VARCHAR(100) NOT NULL
message TEXT NOT NULL
status ENUM('new', 'contacted', 'closed') DEFAULT 'new'
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
```

## Validation Rules

- Full Name: required, minimum 3 characters
- Email: required, valid email format
- Mobile: required, 10 digits
- City: required
- Service: required
- Message: required, minimum 10 characters

## Services Available

- Income Tax Return Filing
- GST Registration & Filing
- PAN Card Services
- Digital Signature Certificates
- Company Registration
- MSME / Udyam Registration
- TDS & ROC Filing
- Financial & Investment Advisory
- Other

## Security Features

- PDO prepared statements for SQL injection prevention
- `htmlspecialchars()` for safe output rendering
- Password hashing with `password_hash()`
- Password verification with `password_verify()`
- Session checks on admin pages
- Server-side validation for inquiry submissions

## Useful Files

- `public/index.php` - public landing page and inquiry form
- `public/css/style.css` - responsive UI styling
- `public/js/script.js` - smooth scrolling and form validation helpers
- `admin/login.php` - admin authentication
- `admin/dashboard.php` - admin summary dashboard
- `admin/manage_inquiries.php` - inquiry search, filtering, update, and delete
- `config/database.php` - database connection and table bootstrap
- `sql/database.sql` - database schema
- `change_admin_password.php` - local utility for changing admin password

## Browser Support

- Chrome
- Firefox
- Edge
- Safari
- Mobile browsers

## Submission Checklist

- Inquiry form with validation
- Admin login and logout
- Admin dashboard with statistics
- Inquiry management page
- Search and status filtering
- Status update support
- Delete inquiry support
- MySQL database schema
- PDO prepared statements
- Password hashing
- Session-based route protection
- Responsive UI
- README setup instructions

## License

This project is provided as part of the PHP Developer Internship Assignment.

Created: April 2026  
Version: 1.0
