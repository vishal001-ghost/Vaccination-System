-- 1. Create Database (drops the old copy so you can re-import safely)
DROP DATABASE IF EXISTS vaccination_db;
CREATE DATABASE IF NOT EXISTS vaccination_db;
USE vaccination_db;

-- 2. Create Admin Table
CREATE TABLE IF NOT EXISTS admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Create Parents Table
CREATE TABLE IF NOT EXISTS parents (
    parent_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Create Hospitals Table
CREATE TABLE IF NOT EXISTS hospitals (
    hospital_id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    location_details VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Create Children Table
CREATE TABLE IF NOT EXISTS children (
    child_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL,
    child_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES parents(parent_id) ON DELETE CASCADE
);

-- 6. Create Vaccines Table
CREATE TABLE IF NOT EXISTS vaccines (
    vaccine_id INT AUTO_INCREMENT PRIMARY KEY,
    vaccine_name VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('Available', 'Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Create Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL,
    child_id INT NOT NULL,
    hospital_id INT NOT NULL,
    vaccine_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected', 'Vaccinated', 'Not Vaccinated') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES parents(parent_id) ON DELETE CASCADE,
    FOREIGN KEY (child_id) REFERENCES children(child_id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id) ON DELETE CASCADE,
    FOREIGN KEY (vaccine_id) REFERENCES vaccines(vaccine_id) ON DELETE CASCADE
);

-- ---------- Starting data ----------
-- admin login: admin@admin.com / admin1234
INSERT INTO admin (username,password) VALUES ('admin@admin.com','$2b$10$NVCQu0oMJ5qDkXoWcNEsmeH0nERYdWcBqRv/qy5WJLvY3mZQaLgrm');

-- hospital logins: hospital1@demo.com / hosp1 ... hospital15@demo.com / hosp15
INSERT INTO hospitals (hospital_name,email,password,address,location_details) VALUES
('Aga Khan University Hospital','hospital1@demo.com','$2b$10$HqOZUSoDUpn5DOTWzA5EUeXEJauoRELRlv6G7jPWDSb7ZeuouTHue','Stadium Road, Karachi','Stadium Road'),
('Indus Hospital','hospital2@demo.com','$2b$10$eRGiFSVNjkFgOCtwohtO3.GR5h2tum702e27nZqgvjtyJMlVwKk5G','Korangi, Karachi','Korangi'),
('Liaquat National Hospital','hospital3@demo.com','$2b$10$zWKOuSQHwCO2iL0IqHyi4eP71Ji7x.nNihjx4b3q/KN.cnivh0h0O','Stadium Road, Karachi','Stadium Road'),
('Dr. Ziauddin Hospital','hospital4@demo.com','$2b$10$pFfxnPpQpvEJ6d1l3lw0ROo4VKvuncN7VHMIm0FO9ZOzap/jjhGAy','Clifton, Karachi','Clifton'),
('South City Hospital','hospital5@demo.com','$2b$10$UsUBBVdjMfV51/0oS4cr.Ob99gQMFVjiPJiAUTFBj.pM.xji7Sm/m','Clifton, Karachi','Clifton'),
('Jinnah Postgraduate Medical Centre','hospital6@demo.com','$2b$10$ChdaODmX6HmOXjQKVzoDSeBtoSD1GO9QAWRC8lHWVIlfxz0SbwaE2','Rafiqui Shaheed Road, Karachi','Saddar'),
('Civil Hospital Karachi','hospital7@demo.com','$2b$10$xfBnn9QNoL4LYSzUH0s15Oh6uxL7278fUCf3vLKJU9b9pxj3aZ8Vu','Saddar, Karachi','Saddar'),
('Dow University Hospital','hospital8@demo.com','$2b$10$82lUaiWwBD32FmdcgVPml.DxS6Y8FL6HmBeatOMH3aGc4w4KwWBpS','Gulshan-e-Iqbal, Karachi','Gulshan-e-Iqbal'),
('National Institute of Child Health','hospital9@demo.com','$2b$10$E23JIxkn30brKEZO1LtsQuBFReuzDVzasKXa/97LquL2ni3FgDHli','Rafiqui Shaheed Road, Karachi','Saddar'),
('Saifee Hospital','hospital10@demo.com','$2b$10$Bzge7QSm6XWDQ/tpLrQAuOG6/T/0sjlWIDJmQnAht80eO4zBAcEcm','North Nazimabad, Karachi','North Nazimabad'),
('Abbasi Shaheed Hospital','hospital11@demo.com','$2b$10$ouflZepFabD2nyQ3fLkwhuRzFmjRe0LwU9Xx3KH1ArF2aZgdkuy/6','Nazimabad, Karachi','Nazimabad'),
('Sindh Government Qatar Hospital','hospital12@demo.com','$2b$10$BxfhdqU2zg6f7xEkiDBPbObKBC8iTj2bwH1LGgCBkkUCXbDtfFE6O','Orangi Town, Karachi','Orangi Town'),
('Lyari General Hospital','hospital13@demo.com','$2b$10$vBHA2IzGPRHsuxqbEGwbQOgtkmiUWao54zV4EsZb9r6a2Nq5ISzy.','Lyari, Karachi','Lyari'),
('Sindh Government Hospital Liaquatabad','hospital14@demo.com','$2b$10$3sl53jv3SXi0QXCNSwqww.Sxa3h7gPZOKbTagNg3BoirIBNtyOAEy','Liaquatabad, Karachi','Liaquatabad'),
('Hill Park General Hospital','hospital15@demo.com','$2b$10$kfbeP26uNkQ9vdcWZbPy6OXUMUdvaxbu.6pApdobrrprLLwnS/Him','PECHS, Karachi','PECHS');

INSERT INTO vaccines (vaccine_name,description,status) VALUES
('BCG','Protects against tuberculosis (TB), given at birth.','Available'),
('Hepatitis B','Protects against hepatitis B liver infection.','Available'),
('Polio (OPV)','Oral drops that protect against polio.','Available'),
('Polio (IPV)','Injected vaccine that protects against polio.','Available'),
('Pentavalent (DPT-HepB-Hib)','Protects against diphtheria, tetanus, pertussis, hepatitis B and Hib.','Available'),
('Pneumococcal (PCV13)','Protects against pneumococcal pneumonia and meningitis.','Available'),
('Rotavirus','Protects against severe diarrhoea caused by rotavirus.','Available'),
('Measles','Protects against measles.','Available'),
('MMR','Protects against measles, mumps and rubella.','Unavailable'),
('Typhoid Conjugate','Protects against typhoid fever.','Available'),
('Hepatitis A','Protects against hepatitis A liver infection.','Available'),
('Varicella (Chickenpox)','Protects against chickenpox.','Unavailable'),
('Influenza','Yearly flu vaccine.','Available'),
('Meningococcal','Protects against meningococcal meningitis.','Unavailable'),
('DTP Booster','Booster dose for diphtheria, tetanus and pertussis.','Available');
