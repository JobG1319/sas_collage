
-- Create admin table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin (username: admin, password: 1234)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$7voocgchSzE.rhd2wiVEhOiuEji/nqch/smnyAOahH92NgmMYL4/y');
