CREATE DATABASE payment_gateway;
USE payment_gateway;

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id VARCHAR(255) NOT NULL,
    intent VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) NOT NULL,
    payer_email VARCHAR(255),
    create_time DATETIME NOT NULL,
    update_time DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (payment_id)
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id VARCHAR(255) NOT NULL,
    transaction_id VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    fee DECIMAL(10, 2),
    net_amount DECIMAL(10, 2),
    currency VARCHAR(3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(payment_id),
    UNIQUE KEY (transaction_id)
);