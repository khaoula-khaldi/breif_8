--creation de la base de données 
CREATE DATABASE smart_wallet; 

USE  smart_wallet; 

--creation de tableau user 
CREATE TABLE utilisateur (
    id int PRIMARY KEY AUTO_INCREMENT,
    nomcomplet  varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    password varchar(255) NOT NULL
);
 
--creation table category 
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    limite_mensuelle DECIMAL(10,2) NOT NULL DEFAULT 0,
    nom VARCHAR(100) NOT NULL
);

--creation table carte 
CREATE TABLE carte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    solde FLOAT DEFAULT 0,
    category_id INT NOT NULL  ,
    FOREIGN KEY (user_id) REFERENCES utilisateur(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE ON UPDATE CASCADE
);


--creation de tableau Incomes 
CREATE TABLE Incomes (
    idIn int PRIMARY KEY AUTO_INCREMENT,
    MontantIn DECIMAL(10,2) NOT NULL,
    descreptionIn varchar(255) NOT NULL,
    date_enterIn DATE NOT NULL,
    category VARCHAR(255),
    user_id int,
    carte_id int,
    category_id int,
    FOREIGN KEY (user_id) REFERENCES utilisateur(id),
    FOREIGN KEY (carte_id) REFERENCES carte(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);

--creation de tableau Expenses  
CREATE TABLE Expenses  (
    idEx int PRIMARY KEY AUTO_INCREMENT,
    MontantEx DECIMAL(10,2) NOT NULL,
    descreptionEx varchar(255) NOT NULL,
    date_enterEx DATE NOT NULL,
    category VARCHAR(255),
    user_id int,
    carte_id int,
    category_id int,
    FOREIGN KEY (user_id) REFERENCES utilisateur(id),
    FOREIGN KEY (carte_id) REFERENCES carte(id),
    FOREIGN KEY (category_id) REFERENCES category(id)
);



--creation table transaction carte 
CREATE TABLE CREATE TABLE transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    carte_source_id INT NOT NULL,
    carte_dest_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES utilisateur(id),
    FOREIGN KEY (carte_source_id) REFERENCES carte(id),
    FOREIGN KEY (carte_dest_id) REFERENCES carte(id)
);

--creation table transaction user
CREATE TABLE transactions_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_send INT NOT NULL,      
    user_dest INT NOT NULL,       
    carte_dest INT NOT NULL,    
    carte_send INT NOT NULL,      
    montant DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    date_trans TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_send) REFERENCES utilisateur(id),
    FOREIGN KEY (user_dest) REFERENCES utilisateur(id),
    FOREIGN KEY (carte_send) REFERENCES carte(id),
    FOREIGN KEY (carte_dest) REFERENCES carte(id)
);

--creation table otp
CREATE TABLE otp_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code VARCHAR(6) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateur(id)
);


