# Sweet Delights Bakery  
A dynamic website for a small bakery that allows users to browse products, place orders, and leave reviews. Includes an admin panel for managing products and orders.

<img width="639" height="299" alt="image" src="https://github.com/user-attachments/assets/d90c6b08-37cc-4cd0-be81-3e9ce8e55701" />

## Project Aim:
To create a full-featured bakery website with a user-friendly interface and backend functionality for order and product management.
    
## Features  
### User Side
- **Main Page**: Bakery description 
- **Our Products**: Dynamically loaded list of baked goods with description, prices, and images
- **Order Page**: Online order form connected to the database
- **Customer Reviews**: Display of customer feedback

### Admin Panel
- View and manage customer orders
- Add/edit/delete bakery products
- Review customer messages or reviews


## Technologies Used  
- Frontend : 
    ![HTML](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
    ![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

  
- Backend : 
    ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
    ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
    ![XAMPP](https://img.shields.io/badge/XAMPP-007396?style=for-the-badge&logo=xampp&logoColor=white)

## How to Run 

1. **Install [XAMPP](https://www.apachefriends.org/index.html)**
2. **Clone or copy the project** into your `htdocs` folder (e.g. `C:\xampp\htdocs\sweet-delights`).
3. **Start Apache and MySQL** from the XAMPP control panel.
4. Import the SQL file into phpMyAdmin:
   - Visit `http://localhost/phpmyadmin`
   - Create a new database (`bakery_db`)
   - Import the provided `.sql` file inside the `database/` folder
5. Open the site in your browser:
   - `http://localhost/sweet-delights`

## Future Improvements  
- Email/SMS order notifications  
- Responsive design for mobile devices
- Special offers section for holidays
