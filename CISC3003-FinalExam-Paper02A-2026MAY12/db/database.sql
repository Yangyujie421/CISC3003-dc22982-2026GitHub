CREATE DATABASE IF NOT EXISTS examA CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE examA;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(20) NOT NULL,
    course VARCHAR(80) NOT NULL,
    hobbies VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    agree_terms TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO students (full_name, email, age, gender, course, hobbies, message, agree_terms)
VALUES ('Demo Student', 'demo@example.com', 20, 'Other', 'CISC3003', 'Coding, Reading', 'This is a sample INSERT INTO record.', 1);
