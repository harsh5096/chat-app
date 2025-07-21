# ChatApp Deployment Checklist

## ✅ Database Configuration
- [x] Database connection file (conn.php) exists
- [x] Database credentials are configured
- [x] Database "chat" exists with required tables

## ✅ Core Files Status
- [x] login.php - Working
- [x] registration.php - Working  
- [x] chat.php - Working
- [x] logout.php - Working
- [x] users.php - Working
- [x] functions.php - Working
- [x] conn.php - Working

## ⚠️ Issues Found & Fixed

### 1. External Dependencies
- [ ] Remove references to ../landingpagejs/ (external JS files)
- [ ] Remove references to ../img/ (external images)
- [ ] Remove references to ../index.php (external pages)

### 2. Database Port
- [ ] Update conn.php port from 3306 to 3307 (if needed)

### 3. File Paths
- [ ] All internal paths should be relative to current directory
- [ ] No parent directory references (../)

## 🔧 Required Fixes for Deployment

### 1. Remove External Dependencies
```php
// Remove these lines from login.php and registration.php:
<script src="../landingpagejs/admin-login.js"></script>
<script src="../landingpagejs/get-in-touch.js"></script>
<script src="../landingpagejs/how-its-work.js"></script>
<script src="../landingpagejs/Pos.js"></script>
```

### 2. Update Navigation Links
```php
// Change from:
<a href="../index.php">Home</a>
// To:
<a href="#">Home</a>
```

### 3. Remove External Image References
```php
// Change from:
<link rel="icon" type="image/png" href="../img/3.svg">
// To:
<link rel="icon" type="image/png" href="logo-h.svg">
```

## 📁 Required Database Tables

### user table
```sql
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);
```

### message table
```sql
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_email` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `time` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_email` (`sender_email`),
  KEY `receiver_email` (`receiver_email`)
);
```

## 🚀 Deployment Steps

1. **Database Setup**
   - Create database named "chat"
   - Import the SQL tables above
   - Update conn.php with correct credentials

2. **File Upload**
   - Upload all files to web server
   - Ensure image/ directory is writable
   - Set proper file permissions

3. **Configuration**
   - Update database connection details
   - Test all functionality
   - Remove any development-specific code

4. **Testing**
   - Test user registration
   - Test user login
   - Test chat functionality
   - Test file uploads
   - Test logout

## 📋 Pre-Deployment Checklist

- [ ] All external dependencies removed
- [ ] Database tables created
- [ ] File permissions set correctly
- [ ] All forms working
- [ ] Image uploads working
- [ ] Chat functionality tested
- [ ] Session management working
- [ ] Security measures in place 