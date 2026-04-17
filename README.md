# 📚 Modern Library Management System



A professional, high-end Library Management System built with **PHP** and **MySQL**, featuring a stunning **Glassmorphism UI** and role-based access control.

## ✨ Features

- **🛡️ Secure Authentication**: Built-in login and registration system with password hashing.
- **👥 Role-Based Access**: Specialized dashboards for both **Admins** and **Users**.
- **📊 Admin Analytics**: Real-time tracking of total books, active rentals, and user stats.
- **📚 Book Management**: Full CRUD functionality to manage the library catalog.
- **🔄 Rental System**: Easy-to-use interface for renting and returning books with status tracking.
- **📄 Professional Reports**: Detailed rental logs and returned book history.
- **💎 Glassmorphism Design**: Modern, animated UI with vibrant gradients and micro-interactions.

## 🚀 Tech Stack

- **Backend**: PHP 8.x
- **Database**: MySQL
- **Frontend**: HTML5, CSS3 (Custom Glassmorphism Tokens), FontAwesome Icons
- **Server**: XAMPP / Apache

## 🛠️ Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Harshmarodia/library-management-system.git
   ```

2. **Database Setup**:
   - Open PHPMyAdmin.
   - Create a new database named `library_db`.
   - Import the `library_db.sql` file located in the root directory.

3. **Configure Connection**:
   - Rename `config.php.example` to `config.php`.
   - Update your database credentials:
   ```php
   $host = "localhost";
   $user = "your_username";
   $pass = "your_password";
   $dbname = "library_db";
   ```

4. **Run Application**:
   - Move the project folder to `htdocs` (if using XAMPP).
   - Access via `http://localhost/library-system`.

## 🔑 Default Credentials

- **Admin**: `admin@library.com` | Password: `admin123`
- **User**: `aarav@example.com` | Password: `user123`

## 📸 Preview

*The application features a fully responsive design with dynamic sidebar navigation and glass-style data cards.*

---
Developed by [Harsh Marodia](https://github.com/Harshmarodia)
