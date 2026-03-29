# 🎉 Event Management System

<div align="center">
  <img src="assets/dashboard.png" alt="Project Screenshot" width="800">
</div>

<p align="center">
  <i>A comprehensive, web-based platform to automate event planning, participant registration, and overall event management.</i>
</p>

## ✨ Features

- 👥 **Role-Based Access:** Dedicated dashboards for Admins, Staff, and Students.
- 📅 **Event Creation & Tracking:** Admins can effortlessly create, edit, and monitor events.
- 📝 **Streamlined Registration:** Users can easily browse events, register, and manage their profiles.
- 📧 **Automated Email Service:** Built-in Java-based email notifications for registrations and updates.
- 🔒 **Secure Data Handling:** Robust database architecture ensuring data integrity and secure access.

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP, Java (Email Service API)
- **Database:** MySQL

## 📂 Project Structure

- `Admin/`, `Staff/`, `Student/` - Role-specific dashboards and logic.
- `Login/`, `Register/` - Authentication modules.
- `API/`, `java-email-service/` - Backend API and email notification service.
- `Database/` - SQL schemas and database setup files.
- `Config/` - Application configurations (e.g., database connection strings).
- `assets/` - Static files like images, CSS, and JS.
- `Documentation/` - Project reports and guides.

## 🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine.

### Prerequisites

- [XAMPP](https://www.apachefriends.org/index.html) / WAMP / MAMP (or any local server with PHP & MySQL)
- Web Browser
- Java 8+ (for the email service)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/Pxrvn07/Event-Management-System.git
   ```
2. Move the project folder to your local server directory:
   - For XAMPP: Move to `C:\xampp\htdocs\`
   - For WAMP: Move to `C:\wamp\www\`
3. Start **Apache** and **MySQL** from your server control panel.
4. Set up the database:
   - Open your browser and go to `http://localhost/phpmyadmin/`
   - Create a new database.
   - Import the database from the `Database/` folder.
5. Setup Configuration:
   - Update `Config/` files with your local database credentials if needed.
6. Open the project in your browser:
   ```
   http://localhost/Event-Management-System
   ```

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! 
Feel free to check out the [issues page](https://github.com/Pxrvn07/Event-Management-System/issues). If you'd like to contribute, please fork the repository and create a pull request.

## ⭐ Show your support

Give a ⭐️ if you like this project!
