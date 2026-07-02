CREATE DATABASE IF NOT EXISTS web_ban_hang
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE web_ban_hang;

-- Drop existing tables to prevent "Table already exists" error
DROP TABLE IF EXISTS order_details;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- =========================================
-- USERS
-- =========================================

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
fullname VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
role ENUM('admin','customer') DEFAULT 'customer',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- CATEGORIES
-- =========================================

CREATE TABLE categories (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL
);

-- =========================================
-- PRODUCTS
-- =========================================

CREATE TABLE products (
id INT AUTO_INCREMENT PRIMARY KEY,
category_id INT,
name VARCHAR(255) NOT NULL,
description TEXT,
price DECIMAL(12,2) NOT NULL,
quantity INT DEFAULT 0,
image VARCHAR(255),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (category_id)
REFERENCES categories(id)
ON DELETE SET NULL
);

-- =========================================
-- ORDERS
-- =========================================

CREATE TABLE orders (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
total_money DECIMAL(12,2) NOT NULL,
payment_method VARCHAR(50) DEFAULT 'COD',
status ENUM(
    'Pending',
    'Processing',
    'Shipping',
    'Completed',
    'Cancelled'
) DEFAULT 'Pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id)
REFERENCES users(id)
ON DELETE CASCADE
);

-- =========================================
-- ORDER DETAILS
-- =========================================

CREATE TABLE order_details (
id INT AUTO_INCREMENT PRIMARY KEY,
order_id INT NOT NULL,
product_id INT NOT NULL,
quantity INT NOT NULL,
price DECIMAL(12,2) NOT NULL,
FOREIGN KEY (order_id)
REFERENCES orders(id)
ON DELETE CASCADE,
FOREIGN KEY (product_id)
REFERENCES products(id)
ON DELETE CASCADE
);

-- =========================================
-- SAMPLE CATEGORY DATA
-- =========================================

INSERT INTO categories(name)
VALUES
('Laptop'),
('Điện thoại'),
('Phụ kiện'),
('Gaming'),
('Màn hình');

-- =========================================
-- ADMIN ACCOUNT
-- Username: admin
-- Password: admin123
-- =========================================

INSERT INTO users
(
username,
password,
fullname,
email,
role
)
VALUES
(
'admin',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'Administrator',
'admin@gmail.com',
'admin'
);

-- =========================================
-- SAMPLE PRODUCTS
-- =========================================

INSERT INTO products
(
category_id,
name,
description,
price,
quantity,
image
)
VALUES
(
1,
'Dell Inspiron 15',
'Laptop học tập và văn phòng',
15990000,
20,
'dell.jpg'
),
(
1,
'MacBook Air M2',
'Laptop Apple M2',
25990000,
10,
'macbook.jpg'
),
(
2,
'iPhone 15',
'Điện thoại Apple',
21990000,
15,
'iphone15.jpg'
),
(
2,
'Samsung Galaxy S25',
'Điện thoại Samsung',
19990000,
12,
's25.jpg'
),
(
3,
'Tai nghe Logitech G435',
'Tai nghe gaming không dây',
1290000, 30, 'g435.jpg'
);
