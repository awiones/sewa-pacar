<div align="center">
  <br>
  <img src="LOGO.png" alt="logo" width="400px;">
</div>
<p align="center">
  <a href=""><img src="https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat"></a>
  <a href="http://sewapacar.kesug.com/"><img src="https://img.shields.io/badge/Website-Here-brightgreen.svg?style=flat"></a>
</p>

# Rent a Girlfriend

## Overview
"Rent a Girlfriend" is a web application built using PHP and MySQL that allows users to browse and rent virtual girlfriends. This project serves as my first foray into PHP programming and database management, showcasing my learning journey in web development.

## Features
- **User Registration**: Users can register as girlfriends and be listed on the platform.
- **Dynamic Listings**: Users can view available girlfriends with detailed information, including age, location, personality, and price.
- **Responsive Design**: The application automatically redirects mobile users to a mobile-friendly version of the site.
- **Rating System**: Users can leave reviews and ratings for girlfriends, enhancing the overall experience.
- **Admin Dashboard**: Admin users can access a dashboard to manage listings and reviews.

## Technologies Used
- **PHP**: Server-side scripting language for dynamic content.
- **MySQL**: Database management system for storing user and girlfriend data.
- **HTML/CSS**: For the structure and styling of the website.
- **Bootstrap**: CSS framework for responsive design.
- **JavaScript**: For interactive features on the frontend.

### Preview
<img src="board1.PNG" alt="Homepage" style="width: 100%; height: auto;">
<img src="board2.PNG" alt="Girlfriend Listing" style="width: 100%; height: auto;">

## Getting Started
To run this project locally, follow these steps:

1. **Clone the repository**:
```bash
https://github.com/awiones/sewa-pacar.git
```

2. **Navigate to the project directory**:
```bash
cd rent-a-girlfriend
```
or maybe just download it at release

3. **Set up MySQL**:
   - Use **XAMPP** or another local server (like MAMP, WAMP, etc.).
   - Open **XAMPP Control Panel** and start the **Apache** and **MySQL** modules.
   - Open your browser and navigate to `http://localhost/phpmyadmin`.

4. **Import the database**:
   - Open the `sql.txt` file included in this repository.
   - Copy the SQL commands from `sql.txt`.
   - In **phpMyAdmin**, create a new database (e.g., `rent_a_girlfriend`).
   - Select the newly created database and go to the **SQL** tab.
   - Paste the copied SQL commands into the SQL query window and click **Go** to execute.

5. **Configure the database connection**:
   - Open the `backend/configure.php` file.
   - Update the database credentials to match your setup:
     ```php
     $host = 'localhost'; // usually 'localhost' for XAMPP
     $port = 3306; // default MySQL port
     $db = 'rent_a_girlfriend'; // the name of your database
     $user = 'root'; // default username for XAMPP
     $pass = ''; // default password for XAMPP (usually empty)
     ```

6. **Access the application**:
   Open your browser and go to:
   ```
   http://localhost/rent-a-girlfriend/
   ```


## Future Improvements
- Implement user authentication with password hashing for added security.
- Enhance the user interface for better user experience.
- Introduce a messaging system for users and girlfriends.
- Optimize database queries for performance.

## Contributing

Contributions are welcome! If you have suggestions, improvements, or bug fixes, please submit a pull request or open an issue.

- **Fork the Repository**: Create a personal copy of the repository on GitHub.
- **Make Changes**: Implement your changes and test them locally.
- **Submit a Pull Request**: Describe your changes and submit a pull request for review.

Feel free to use, modify, and build upon this project. If you make any improvements or modifications, please give credit to the original creator.


## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---
Made with ❤ Awiones
