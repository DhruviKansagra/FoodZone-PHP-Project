# 🍔 FoodZone — Online Food Ordering System

A database-driven online food ordering web application developed using **PHP, MySQL, HTML5, CSS3, JavaScript, and Bootstrap**. This project demonstrates backend development, database integration, CRUD operations, food management, order processing, and responsive web design.

[![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.1-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![HTML5](https://img.shields.io/badge/HTML5-Markup-E34F26?logo=html5&logoColor=white)](https://html.spec.whatwg.org/)
[![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?logo=css3&logoColor=white)](https://www.w3.org/Style/CSS/)

## 📌 Table of Contents

- [Project Overview](#-project-overview)
- [Features](#-features)
- [Technology Stack](#️-technology-stack)
- [Project Architecture](#️-project-architecture)
- [Project Structure](#-project-structure)
- [Installation and Setup](#️-installation-and-setup)
- [How to Use](#-how-to-use)
- [Database Structure](#️-database-structure)
- [Project Links](#-project-links)
- [Screenshots](#-screenshots)
- [Challenges and Solutions](#-challenges-and-solutions)
- [Skills Demonstrated](#-skills-demonstrated)
- [Future Improvements](#-future-improvements)
- [Contributing](#-contributing)
- [License](#-license)
- [Project Summary](#-project-summary)

## 🎯 Project Overview

**FoodZone** is a PHP-based online food ordering web application designed to make the food ordering process easier and more convenient.

The application allows customers to browse food categories, view food details, add food items to a shopping cart, and place orders online.

The project also provides an admin panel through which administrators can manage food categories, food items, customers, orders, shipping information, and customer feedback.

PHP handles server-side processing, while MySQL stores and manages application data. HTML, CSS, JavaScript, and Bootstrap are used to build the user interface.

### Project Objectives

- Develop a user-friendly online food ordering platform.
- Implement customer registration and login functionality.
- Display food items dynamically from the database.
- Organize food items by category.
- Implement shopping cart and order placement functionality.
- Provide administrative features for managing food and orders.
- Maintain customer, food, and order information in a MySQL database.
- Practice web development and database management.

## ✨ Features

### Customer-Facing Features

- **User Registration:** Create a new customer account.
- **User Login:** Sign in to an existing account.
- **Profile Management:** Manage personal profile information.
- **Food Categories:** Browse food items by category.
- **Food Menu:** Display food names, images, descriptions, and prices.
- **Food Search:** Search for available food items.
- **Shopping Cart:** Add food items, update quantities, and remove items.
- **Buy Now:** Use the quick-order option.
- **Order Placement:** Place orders by providing delivery information.
- **Contact Page:** Send messages and inquiries.
- **Feedback:** Submit feedback and suggestions.

### Administrative Features

- **Admin Dashboard:** View summaries of customers, categories, food items, orders, and feedback.
- **Category Management:** Add, view, edit, and delete food categories.
- **Food Management:** Add, view, edit, and delete food items.
- **User Management:** View registered customer information.
- **Order Management:** View and manage customer orders.
- **Shipping Management:** Manage customer delivery information.
- **Feedback Management:** View customer feedback and contact messages.
- **Database Integration:** Perform database operations using PHP and MySQL.

*Note: Ensure that each feature listed above is implemented and working in your current version.*

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Backend development and server-side processing |
| MySQL | Relational database management |
| HTML5 | Web page structure |
| CSS3 | Styling and layouts |
| JavaScript | Client-side interactions |
| Bootstrap | Responsive UI components |
| XAMPP | Local development environment |
| Visual Studio Code | Source-code editor |
| Git and GitHub | Version control and source-code hosting |

## 🏗️ Project Architecture

FoodZone follows a basic client-server architecture.

### Application Flow

1. **Customer / Administrator:** Uses the application.
2. **Web Browser:** Displays the website.
3. **Frontend:** HTML, CSS, JavaScript, and Bootstrap create the user interface.
4. **PHP Backend:** Processes forms and application logic.
5. **MySQL Database:** Stores and retrieves application data.
6. **Dynamic HTML Output:** PHP sends the generated content back to the browser.

### Application Workflow

1. The customer or administrator opens the application in a web browser.
2. The browser displays the frontend developed using HTML, CSS, JavaScript, and Bootstrap.
3. PHP processes requests, form submissions, and application logic.
4. PHP communicates with MySQL to retrieve or update information.
5. MySQL processes the database queries.
6. PHP generates dynamic page content.
7. The browser displays the result.

## 📁 Project Structure

The following is a representative structure. Ensure the filenames match your actual repository.

- **FoodZone/**
  - index.php
  - about.php
  - foods.php
  - food-details.php
  - cart.php
  - contact.php
  - login.php
  - registration.php
  - profile.php
  - connected.php
  - **admin/**
    - dashboard.php
    - category.php
    - add_category.php
    - edit_category.php
    - food.php
    - add_food.php
    - edit_food.php
    - orders.php
    - users.php
    - feedback.php
  - **css/**
  - **js/**
  - **images/**
  - **screenshots/**
    - home.png
    - foods.png
    - category-management.png
    - food-management.png
  - database.sql
  - README.md

*Note: This is a representative structure. Update the folder and file names according to your actual project.*

## ⚙️ Installation and Setup

### Prerequisites

- XAMPP
- Visual Studio Code or another code editor
- A modern web browser
- Git (optional)

### Step 1: Clone the Repository

Run this command in your terminal:

`git clone https://github.com/DhruviKansagra/FoodZone.git`

Alternatively, open the repository on GitHub and select **Code → Download ZIP**.

### Step 2: Move the Project to XAMPP

Extract the project into:

`C:\xampp\htdocs\FoodZone`

Ensure that the PHP files and asset folders are inside the project directory.

### Step 3: Start XAMPP

1. Open the XAMPP Control Panel.
2. Start Apache.
3. Start MySQL.

### Step 4: Set Up the Database

1. Open [phpMyAdmin](http://localhost/phpmyadmin).
2. Create a MySQL database using the name configured in your project.
3. Select the database.
4. Import the project's SQL file if it is included in the repository.
5. Confirm that the required tables exist.

Expected table names based on the project documentation:

- `User`
- `Contact`
- `Categories`
- `Foods`
- `Cart`
- `Orders`
- `Shipping`

If these tables already exist, do not create duplicate tables.

### Step 5: Configure the Database Connection

Open the project's database connection PHP file and verify the connection settings.

Example settings for a default local XAMPP installation:

- **Host:** localhost
- **Username:** root
- **Password:** Empty (default local XAMPP example)
- **Database:** your_database_name

Replace `your_database_name` with the actual database name used by your project. Your existing PHP pages must use the same connection variable expected by your code.

**Important:** The empty password is only an example for a default local XAMPP installation. Never publish production credentials in your GitHub repository.

### Step 6: Run the Application

Open the following URL in your browser:

`http://localhost/FoodZone/`

The exact starting page depends on your project's file structure.

The application should now run locally on your computer.

## 🚀 How to Use

### Customer Side

1. Open the FoodZone home page.
2. Register for a new account or log in.
3. Browse the available food categories.
4. View food items and their details.
5. Add food items to the shopping cart or use the Buy Now option.
6. Review the cart and update quantities if required.
7. Provide delivery information.
8. Place the order.
9. Use the Contact page to send messages or submit feedback.

### Admin Side

1. Open the admin login page.
2. Sign in using the configured administrator account.
3. Open the admin dashboard.
4. Add, edit, or delete food categories.
5. Manage food items and their details.
6. View registered customers.
7. View and manage customer orders.
8. Manage shipping information.
9. Review customer feedback and contact messages.

## 🗄️ Database Structure

The project uses a MySQL database. The database name and table definitions should match your actual SQL database.

| Table | Description |
|---|---|
| `User` | Stores customer account and profile information. |
| `Contact` | Stores customer contact messages. |
| `Categories` | Stores food categories. |
| `Foods` | Stores food item details, including category ID, food name, description, price, and image. |
| `Cart` | Stores cart items, user IDs, food details, quantities, and total prices. |
| `Orders` | Stores customer order details, food information, quantities, prices, payment method, and order date. |
| `Shipping` | Stores delivery information, including user ID, order ID, customer name, address, and phone number. |

### Database Relationship

The main database relationships are:

- **Categories → Foods:** One category can contain multiple food items.
- **Foods → Cart:** Food items can be added to customer carts.
- **User → Cart:** Cart records are associated with customers.
- **User → Orders:** Customers can place orders.
- **Orders → Shipping:** Shipping information is associated with orders.
- **Contact:** Stores customer messages.

The `c_id` field in the `Foods` table associates food items with categories. Cart and order records are associated with customers and food items.

*Note: The exact columns and database relationships depend on your actual MySQL schema.*

## 🔗 Project Links

- **GitHub Repository:** [View FoodZone Source Code](https://github.com/DhruviKansagra/FoodZone)
- **GitHub Profile:** [DhruviKansagra](https://github.com/DhruviKansagra)
- **Screenshots:** Browse the `screenshots/` folder.
- **Local Application:** Run the project using XAMPP by following the installation instructions above.

**Live Demo:** Not currently available. The project runs locally using XAMPP.

## 📸 Screenshots

Screenshots showcase the actual application interface while running locally.

### Home Page

![FoodZone Home Page](screenshots/home.jpeg)

### Food Menu

![FoodZone Food Menu](screenshots/foods.jpeg)

### Category Management

![FoodZone Category Management](screenshots/category-management.jpeg)

### Food Management

![FoodZone Food Management](screenshots/food-management.jpeg)

*Note: Upload the actual screenshot files to the `screenshots/` directory in your repository. Ensure the filenames and extensions match the Markdown image paths exactly.*

## 🧩 Challenges and Solutions

The following are relevant development areas. Keep the descriptions that accurately reflect your work.

| Challenge | Approach |
|---|---|
| PHP and MySQL connectivity | Connected the PHP application to MySQL for storing and retrieving project data. |
| User authentication | Implemented registration and login workflows. |
| Food category organization | Associated food items with categories using category identifiers. |
| Dynamic food listings | Retrieved food records from the database and displayed them using PHP. |
| Cart management | Managed selected food items, quantities, and totals. |
| Order processing | Organized customer order details and delivery information. |
| Admin management | Provided modules for managing categories, food items, users, orders, and feedback. |
| Responsive interface | Used HTML, CSS, JavaScript, and Bootstrap for the website interface. |
| Local development | Used XAMPP to run PHP and MySQL locally. |

### Recommended Security Practices

- Use prepared statements for queries involving user input.
- Hash passwords securely rather than storing plain-text passwords.
- Validate form data on the server.
- Escape HTML output where appropriate.
- Validate uploaded image types and file sizes.
- Protect administrative pages with authentication and authorization.
- Keep private credentials out of GitHub.

## 💡 Skills Demonstrated

- **Backend Development:** PHP, server-side scripting, CRUD operations, and form processing.
- **Database Management:** MySQL, SQL queries, database connectivity, and relational data.
- **Frontend Development:** HTML5, CSS3, JavaScript, Bootstrap, and responsive layouts.
- **Application Development:** User authentication, cart workflows, order processing, and admin modules.
- **Problem Solving:** Debugging, troubleshooting, and frontend-backend integration.
- **Development Tools:** XAMPP, Visual Studio Code, Git, and GitHub.
- **Documentation:** Markdown, setup instructions, and project organization.

## 🔮 Future Improvements

- Integrate secure online payment gateways such as UPI, cards, and digital wallets.
- Add real-time order tracking and delivery status updates.
- Develop Android and iOS mobile applications.
- Add notifications for order updates.
- Introduce customer ratings and reviews.
- Add discount coupons and loyalty rewards.
- Implement personalized food recommendations.
- Support multiple restaurants.
- Improve food search, filtering, and wishlist features.
- Add sales reports and analytics for administrators.
- Strengthen authentication and data protection.

## 🤝 Contributing

Suggestions and improvements are welcome.

1. Fork the repository.
2. Create a feature branch using `git checkout -b feature/your-feature`.
3. Implement and test your changes.
4. Commit your changes using `git add .` and `git commit -m "Describe your changes"`.
5. Push your branch and open a pull request.

Please do not commit passwords, private information, or unrelated files.

## 📄 License

No license has been specified for this project yet.

If you plan to publish the project as open source, choose an appropriate license and add a `LICENSE` file. The MIT License is one common option for projects that permit broad reuse under its terms.

Until a license is added, do not assume that others have permission to reuse or redistribute the code.

## 👩‍💻 Project Summary

- **Project Name:** FoodZone — Online Food Ordering System
- **Project Type:** PHP and MySQL web application
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap, JavaScript
- **Backend:** PHP
- **Development Environment:** XAMPP
- **Code Editor:** Visual Studio Code
- **Source Code:** [FoodZone GitHub Repository](https://github.com/DhruviKansagra/FoodZone)
- **Demo:** Runs locally using XAMPP

This project demonstrates practical experience in web development, database integration, CRUD operations, responsive UI design, online food ordering, and application troubleshooting.
