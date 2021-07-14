-- DATABASE SCRIPT CREATE FOR MYSQL XAMPP
-- REMEMEBER TO CHANGE THE FILE database.php IN CONFIG
 
CREATE TABLE users (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    identification VARCHAR(15) NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) NULL,
    phone VARCHAR(15) NULL,
    user VARCHAR(20) NULL,
    password VARCHAR(32) NULL,
    UNIQUE(identification,user)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    number VARCHAR(10) NOT NULL,
    status CHAR(1) NOT NULL,
    amount_to_pay DECIMAL(4,2) NULL,
    userID INT NULL,
    UNIQUE(number),
    CONSTRAINT FK_orders_users FOREIGN KEY (userID)
    REFERENCES users(id)
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    amount DECIMAL(4,2) NOT NULL,
    date DATE NOT NULL,
    orderID INT NULL,
    CONSTRAINT FK_payments_orders FOREIGN KEY (orderID)
    REFERENCES orders(id)
);

INSERT INTO users (identification,firstname,lastname,user,password)
VALUES('123456789','Jon','Balcacer','ADMIN',md5('123456'))