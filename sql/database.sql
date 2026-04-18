-- Create database
CREATE DATABASE IF NOT EXISTS taxsafar_db;
USE taxsafar_db;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create inquiries table
CREATE TABLE IF NOT EXISTS inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    city VARCHAR(50) NOT NULL,
    service VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (Email: admin@taxsafar.com, Password: Admin@123)
INSERT INTO admins (name, email, password) VALUES 
('Admin User', 'admin@taxsafar.com', '$2y$10$8XwZqH8Y2nKqL7M9pQ1Z9.e8X8Y2nKqL7M9pQ1Z9.e8X8Y2nKqL7M');

-- Create index for faster search
CREATE INDEX idx_email ON inquiries(email);
CREATE INDEX idx_name ON inquiries(full_name);
CREATE INDEX idx_mobile ON inquiries(mobile);
CREATE INDEX idx_status ON inquiries(status);
