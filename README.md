# Hobby Club Management Application

## Project Description
The Hobby Club Management Application allows users to create, join, and manage events within a hobby club. Users can manage their profiles, create new accounts, and participate in events.

## Features
- User registration and login
- Event creation and editing
- Joining and viewing events
- User profile management
- Account deletion and logout

## Requirements
- XAMPP (to run PHP and MySQL)
- Web browser

## Installation Instructions

### 1. Download and Install XAMPP
- [Download XAMPP](https://www.apachefriends.org/index.html) and follow the installation instructions for your operating system.

### 2. Set Up the Database
1. Start XAMPP and start the Apache and MySQL modules.
2. Open your web browser and go to `http://localhost/phpmyadmin`.
3. Create a new database named `hobby_club`.

### 3. Create the Required Tables
- Open the SQL tab in phpMyAdmin.
- Copy and paste the following SQL code to create the necessary tables:

```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL
);

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id)
);

CREATE TABLE participations (
    participation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (event_id) REFERENCES events(event_id)
);

```
### 4. Set Up the Project Files
- Download or clone the project files into the htdocs directory of your XAMPP installation.

### 5. Running the Application
- Open your web browser and go to http://localhost/Hobby.
- You should see the login page of the Hobby Club Management Application.
- You can create a new account or log in with an existing account.

# Demonstration
To see how the application works, you can watch this [YouTube video.](https://youtu.be/Ubmgu1Lq_YU) .
