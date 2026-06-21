-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('client','doctor','admin') NOT NULL DEFAULT 'client',
  phone VARCHAR(30),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services
CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price VARCHAR(100),
  is_group TINYINT(1) DEFAULT 0
);

-- Doctors (linked to users + services)
CREATE TABLE doctors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  service_id INT NOT NULL,
  bio TEXT,
  user_id INT NULL,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Time slots
CREATE TABLE time_slots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slot_label VARCHAR(20) NOT NULL,
  slot_time TIME NOT NULL
);

-- Bookings
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  guest_name VARCHAR(150),
  guest_email VARCHAR(150),
  guest_phone VARCHAR(50),
  service_id INT NOT NULL,
  doctor_id INT NOT NULL,
  slot_id INT NOT NULL,
  booking_date DATE NOT NULL,
  status ENUM('booked','cancelled') NOT NULL DEFAULT 'booked',
  payment_status ENUM('pending','received') NOT NULL DEFAULT 'pending',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
  FOREIGN KEY (slot_id) REFERENCES time_slots(id) ON DELETE CASCADE
);

-- Messages
CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150),
  phone VARCHAR(20),
  subject VARCHAR(200),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  assigned_to INT NULL,
  FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Reviews
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  rating INT NOT NULL CHECK(rating BETWEEN 1 AND 5),
  review TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Wellness Center
CREATE TABLE wellness_center (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(255) NOT NULL
);

-- Admin user (password = Admin@123)
INSERT INTO users (name,email,password,role) VALUES
('Admin','admin@greenlife.local',
'$2y$10$wH4EclXk3vR1PqlpcLso0ewKiXXAs16MKeNwS6Y.vsdcZV8T3cncG','admin');

-- Services
INSERT INTO services (name,description,price,is_group) VALUES
('Ayurvedic Therapy','Traditional Sri Lankan Ayurvedic treatments','LKR 5,000 - 15,000',0),
('Yoga Classes','Guided yoga training group classes','LKR 4,000',1),
('Meditation Classes','Meditation training group sessions','LKR 3,000',1),
('Nutrition Consultation','Personalized meal plans and dietary assessments','LKR 3,000 - 10,000',0),
('Physiotherapy','Rehabilitation and movement therapy','LKR 4,000 - 12,000',0),
('Massage Therapy','Therapeutic massages','LKR 3,500 - 9,000',0),
('Mental Wellness','Counseling and mental health support','LKR 4,500 - 11,000',0);

-- Doctor users
INSERT INTO users (name, email, password, role) VALUES
('Dr. Priya Jayawardana','doc1@greenlife.local','$2y$10$Ugcd02YpiiV1V4lYwR44U.ehHWeeM7tMgdAcwEvFfCnQmIfdCgD0S','doctor'),
('Rajesh Fernando','doc2@greenlife.local','$2y$10$yXSc6oHPM/KhqrK5zQQYjO6B8DpsXukLXgK0.Lk3y70WqkHxIlUmm','doctor'),
('Meditation Master Kumari','doc3@greenlife.local','$2y$10$lwEQnGpNjfEmSKOS86heweMLihPo.RPbFgtyl5bbHZ5vhknhmnHDa','doctor'),
('Ms. Sandani Perera','doc4@greenlife.local','$2y$10$R5mQ10ZdguA13oShpcJd2OFXqQ0O5LOIzWJ84EtDCCXMcyKmDs5Um','doctor'),
('Dr. Kamal Silva','doc5@greenlife.local','$2y$10$YAjybILyExZ5vUbb.dxx6.QImEhUtBB1iZpLm/yT1s89s86Wc8kqu','doctor'),
('Chamila Wickramasinghe','doc6@greenlife.local','$2y$10$FErzWSgtxwjf8KqpsAkmbu9Y/wDbZ3J1AKkFSX0zx6qujKCr7dc5i','doctor'),
('Dr. Hiran','doc7@greenlife.local','$2y$10$zT2lVG9ivPDQWzuxlG7ld.LPzXhMcN7rbspjLBDRhKYZ7bmN0NPQK','doctor');

-- Doctors linked with user IDs
INSERT INTO doctors (name, service_id, bio, user_id) VALUES
('Dr. Priya Jayawardana',1,'Ayurvedic specialist',2),
('Rajesh Fernando',2,'Yoga instructor',3),
('Meditation Master Kumari',3,'Meditation instructor',4),
('Ms. Sandani Perera',4,'Nutritionist',5),
('Dr. Kamal Silva',5,'Physiotherapist',6),
('Chamila Wickramasinghe',6,'Massage therapist',7),
('Dr. Hiran',7,'Mental wellness counselor',8);

-- Wellness Center sample
INSERT INTO wellness_center (name, description, image) VALUES
('Meditation Garden','Peaceful outdoor space for yoga and meditation sessions','images/meditation_garden.jpeg'),
('Treatment Rooms','Comfortable and serene spaces for therapy sessions','images/treatment_rooms.jpeg'),
('Herb Garden','Organic medicinal herbs grown on-site for Ayurvedic treatments','images/herb_garden.jpeg');

-- Reviews
INSERT INTO reviews (name, rating, review) VALUES
('Nimal Perera',5,'Excellent service, very professional!'),
('Sanjaya Silva',4,'Great yoga sessions, really helped my stress.');

DELIMITER $$
CREATE PROCEDURE fill_slots()
BEGIN
  DECLARE t TIME;
  SET t = '09:00:00';
  WHILE t <= '16:30:00' DO
    INSERT INTO time_slots (slot_label, slot_time) VALUES (DATE_FORMAT(t,'%h:%i %p'), t);
    SET t = ADDTIME(t,'00:30:00');
  END WHILE;
END$$
DELIMITER ;

CALL fill_slots();
DROP PROCEDURE IF EXISTS fill_slots;
