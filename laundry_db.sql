-- Laundry Service Management System Database
-- Import this file using phpMyAdmin.

CREATE DATABASE IF NOT EXISTS laundry_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE laundry_db;

DROP TABLE IF EXISTS laundry_orders;

CREATE TABLE laundry_orders (
    id INT(11) NOT NULL AUTO_INCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(30) NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    weight DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    order_status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    pickup_date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO laundry_orders
(customer_name, contact_number, service_type, weight, price, order_status, pickup_date)
VALUES
('Maria Santos', '09171234567', 'Wash & Fold', 5.00, 250.00, 'Processing', '2026-10-05'),
('Juan Dela Cruz', '09181234567', 'Wash & Dry', 3.00, 180.00, 'Ready for Pickup', '2026-10-04'),
('Ana Reyes', '09201234567', 'Dry Cleaning', 2.00, 300.00, 'Completed', '2026-10-03');
