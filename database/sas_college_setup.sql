
-- Disable foreign key checks for safe table drops
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS courses;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Create students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create courses table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create registrations table
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Insert a test student
INSERT INTO students (full_name, email, password) VALUES
('Test Student', 'student@example.com', '$2y$10$7voocgchSzE.rhd2wiVEhOiuEji/nqch/smnyAOahH92NgmMYL4/y'); -- password: 1234

-- Insert a test course
INSERT INTO courses (name, description) VALUES
('Intro to Programming', 'Learn the basics of programming with Python.');

-- Register student for course
INSERT INTO registrations (student_id, course_id) VALUES
(1, 1);
