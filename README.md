# Sweet Delights Bakery  
A dynamic website for a small bakery that allows users to browse products, place orders, and leave reviews. Includes an admin panel for managing products and orders.

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
- Frontend: HTML, CSS
- Backend: PHP
- Database: MySQL
- Server: XAMPP (Apache + MySQL)

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
