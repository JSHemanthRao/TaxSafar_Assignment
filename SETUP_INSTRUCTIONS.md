# TaxSafar - Setup Instructions

## Quick Start Guide

Follow these steps to get the application running:

### Step 1: Extract Project
- Extract the project to: `C:\xampp\htdocs\TaxSafar_Assignment`

### Step 2: Start XAMPP
- Open XAMPP Control Panel
- Start Apache
- Start MySQL

### Step 3: Create Database
- Open browser and go to: `http://localhost/phpmyadmin`
- Click on "SQL" or use Import feature
- Copy all content from `sql/database.sql`
- Paste it in SQL tab and execute
- Or use Import to select the sql/database.sql file directly

### Step 4: Access Application

**For Customers:**
- Navigate to: `http://localhost/TaxSafar_Assignment/public/index.php`
- Fill and submit inquiry form
- Data will be stored in database

**For Admin:**
- Navigate to: `http://localhost/TaxSafar_Assignment/admin/login.php`
- Use credentials:
  - Email: admin@taxsafar.com
  - Password: Admin@123
- Login to access dashboard and manage inquiries

### Step 5: Database Verification (Optional)
- Go to phpMyAdmin: `http://localhost/phpmyadmin`
- Select database: `taxsafar_db`
- Check tables: `admins` and `inquiries`
- Verify admin user in admins table

## Default Admin Credentials

```
Email: admin@taxsafar.com
Password: Admin@123
```

⚠️ **CHANGE THESE AFTER FIRST LOGIN FOR PRODUCTION**

## File Structure

```
TaxSafar_Assignment/
├── config/
│   └── database.php          # Database configuration
├── public/
│   ├── index.php             # Landing page & inquiry form
│   ├── css/style.css         # Styling
│   └── js/script.js          # JavaScript
├── admin/
│   ├── login.php             # Admin login
│   ├── dashboard.php         # Dashboard
│   ├── manage_inquiries.php  # Inquiry management
│   └── logout.php            # Logout
├── sql/
│   └── database.sql          # Database schema
├── includes/
│   ├── header.php
│   └── footer.php
└── README.md                 # Full documentation
```

## Database Details

**Database Name**: `taxsafar_db`

**Tables**:
1. `admins` - Admin users for login
2. `inquiries` - Customer inquiries

## Troubleshooting

### Issue: "Database Connection Error"
**Solution**: 
- Verify MySQL is running
- Check database credentials in `config/database.php`
- Ensure database `taxsafar_db` was created

### Issue: "Admin Login Not Working"
**Solution**:
- Verify database was imported successfully
- Check admin credentials (admin@taxsafar.com / Admin@123)
- Clear browser cache and try again

### Issue: "Forms Not Submitting"
**Solution**:
- Check browser console for JavaScript errors
- Verify MySQL is running
- Check database connection

### Issue: "404 Not Found"
**Solution**:
- Verify file paths are correct
- Ensure XAMPP Apache is running
- Check URL is: `http://localhost/TaxSafar_Assignment/public/index.php`

## Key Features

✅ **Inquiry Form**
- Full validation (client & server-side)
- Success/error messages
- Stores in database with timestamp

✅ **Admin Authentication**
- Secure login with password hashing
- Session-based authentication
- Protected admin routes

✅ **Admin Dashboard**
- Total inquiries count
- New inquiries count
- Contacted inquiries count
- Closed inquiries count
- Recent inquiries table

✅ **Inquiry Management**
- View all inquiries in table
- Search by name/email/mobile
- Filter by status
- Update inquiry status
- Delete inquiries
- View full messages

✅ **Security**
- PDO prepared statements (SQL injection prevention)
- Password hashing with bcrypt
- Input validation & sanitization
- XSS prevention with htmlspecialchars()

## Contact Information

For issues during setup:
1. Check this guide
2. Review README.md for detailed documentation
3. Verify database connection in `config/database.php`
4. Check browser console for errors

---

**Ready to submit?**
- Include this entire folder as your submission
- Also include `ADMIN_CREDENTIALS.txt` for reference
- Create a GitHub repository or upload as ZIP file
