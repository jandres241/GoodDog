CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  account_type ENUM('admin', 'employee', 'customer') NOT NULL
);

-- Create the patients table
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  species VARCHAR(50) NOT NULL,
  breed VARCHAR(100) NOT NULL,
  age INT NOT NULL,
  owner_id INT NOT NULL,
  FOREIGN KEY (owner_id) REFERENCES users(id)
);

-- Create the appointments table
CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  appointment_date DATETIME NOT NULL,
  reason TEXT NOT NULL,
  employee_id INT NOT NULL,
  FOREIGN KEY (patient_id) REFERENCES patients(id),
  FOREIGN KEY (employee_id) REFERENCES users(id)
);

-- Create the services table
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL
);

-- Create the appointments_services table (many-to-many relationship)
CREATE TABLE appointments_services (
  appointment_id INT NOT NULL,
  service_id INT NOT NULL,
  PRIMARY KEY (appointment_id, service_id),
  FOREIGN KEY (appointment_id) REFERENCES appointments(id),
  FOREIGN KEY (service_id) REFERENCES services(id)
);