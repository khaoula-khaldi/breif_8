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



--creation table transaction  
CREATE TABLE transactions  (
    id int PRIMARY KEY AUTO_INCREMENT,
    id_send int ,
    id_service int,
    montant DECIMAL not null ,
    FOREIGN KEY (id_send) REFERENCES utilisateur(id),
    FOREIGN KEY (id_service) REFERENCES utilisateur(id)
     
);

--creation table otp
CREATE TABLE otp_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code VARCHAR(6) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateur(id)
);


