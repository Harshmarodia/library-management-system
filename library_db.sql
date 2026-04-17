CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
);

-- Books table
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    quantity INT DEFAULT 1
);

-- Rentals table
CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    rent_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    status ENUM('rented','returned') DEFAULT 'rented',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

-- Seed Admin (Password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Super Admin', 'admin@library.com', '$2y$10$RZ.TVcxMPN36.6RcF6PBmOZTUQTPzQGyAAsO/Wc2RmzvIYuzUV85C', 'admin');

-- Seed 35 Users (Password: user123)
-- Hash for 'user123': $2y$10$vY3Z.mSjI9v/Z9UqjOQnEuQZ.U6Qy.x5X.vQv.vQv.vQv.vQv.vQv
-- Using a simpler consistent hash for seeding purposes
SET @user_pass = '$2y$10$dDZpycCZCGgsbp6e8zT89Or/rnhV255amqmAghvguEpNxx8MRzbwS';

INSERT INTO users (name, email, password, role) VALUES 
('Aarav Sharma', 'aarav@example.com', @user_pass, 'user'),
('Aditi Singh', 'aditi@example.com', @user_pass, 'user'),
('Akash Verma', 'akash@example.com', @user_pass, 'user'),
('Ananya Gupta', 'ananya@example.com', @user_pass, 'user'),
('Arjun Reddy', 'arjun@example.com', @user_pass, 'user'),
('Diya Malhotra', 'diya@example.com', @user_pass, 'user'),
('Ishaan Khan', 'ishaan@example.com', @user_pass, 'user'),
('Kavya Nair', 'kavya@example.com', @user_pass, 'user'),
('Manish Patil', 'manish@example.com', @user_pass, 'user'),
('Meera Joshi', 'meera@example.com', @user_pass, 'user'),
('Nikhil Saxena', 'nikhil@example.com', @user_pass, 'user'),
('Pooja Bhatia', 'pooja@example.com', @user_pass, 'user'),
('Pranav Das', 'pranav@example.com', @user_pass, 'user'),
('Riya Kapoor', 'riya@example.com', @user_pass, 'user'),
('Rohan Mehra', 'rohan@example.com', @user_pass, 'user'),
('Sanya Iyer', 'sanya@example.com', @user_pass, 'user'),
('Shaan Desai', 'shaan@example.com', @user_pass, 'user'),
('Sneha Roy', 'sneha@example.com', @user_pass, 'user'),
('Tushar Khandelwal', 'tushar@example.com', @user_pass, 'user'),
('Vanya Aggarwal', 'vanya@example.com', @user_pass, 'user'),
('Vikram Choudhary', 'vikram@example.com', @user_pass, 'user'),
('Zoya Siddiqui', 'zoya@example.com', @user_pass, 'user'),
('Rahul Bose', 'rahul@example.com', @user_pass, 'user'),
('Priya Sharma', 'priya@example.com', @user_pass, 'user'),
('Amit Kumar', 'amit@example.com', @user_pass, 'user'),
('Sonal Singh', 'sonal@example.com', @user_pass, 'user'),
('Kartik Aaryan', 'kartik@example.com', @user_pass, 'user'),
('Sara Ali', 'sara@example.com', @user_pass, 'user'),
('Varun Dhawan', 'varun@example.com', @user_pass, 'user'),
('Alia Bhatt', 'alia@example.com', @user_pass, 'user'),
('Ranbir Kapoor', 'ranbir@example.com', @user_pass, 'user'),
('Deepika Padukone', 'deepika@example.com', @user_pass, 'user'),
('Ranveer Singh', 'ranveer@example.com', @user_pass, 'user'),
('Shah Rukh Khan', 'srk@example.com', @user_pass, 'user'),
('Salman Khan', 'salman@example.com', @user_pass, 'user');

-- Seed 20 Books
INSERT INTO books (title, author, quantity) VALUES 
('The Alchemist', 'Paulo Coelho', 10),
('Rich Dad Poor Dad', 'Robert Kiyosaki', 5),
('Atomic Habits', 'James Clear', 8),
('The Psychology of Money', 'Morgan Housel', 12),
('Deep Work', 'Cal Newport', 7),
('Ikigai', 'Francesc Miralles', 15),
('Thinking, Fast and Slow', 'Daniel Kahneman', 4),
('Sapiens', 'Yuval Noah Harari', 9),
('The Lean Startup', 'Eric Ries', 6),
('A Game of Thrones', 'George R.R. Martin', 3),
('The Hobbit', 'J.R.R. Tolkien', 11),
('1984', 'George Orwell', 8),
('The Great Gatsby', 'F. Scott Fitzgerald', 5),
('Pride and Prejudice', 'Jane Austen', 7),
('To Kill a Mockingbird', 'Harper Lee', 6),
('The Catcher in the Rye', 'J.D. Salinger', 4),
('Brave New World', 'Aldous Huxley', 8),
('The Kite Runner', 'Khaled Hosseini', 10),
('Life of Pi', 'Yann Martel', 12),
('The Book Thief', 'Markus Zusak', 9);
