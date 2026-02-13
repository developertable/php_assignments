# Assignment 4 - Complete Incident Management System

A fully functional multi-user technical support incident tracking system built with PHP, MySQL, and Bootstrap.

## 🎥 Demo Video

**[View Demo Video](video/demo.mp4)**

A complete walkthrough demonstrating all features of the incident management system.

---

## 📋 Projects Completed

### Project 6-1: Manage Products
- CRUD operations for products
- Foreign key constraint validation before deletion
- Checks for dependencies (incidents and registrations)

### Project 6-2: Manage Technicians
- Add and delete support technicians
- Email validation
- Foreign key checks for assigned incidents

### Project 6-3: Manage Customers
- Search customers by last name (LIKE query with wildcards)
- View and update customer information
- Dynamic country dropdown from database

### Project 6-4: Register Product
- Customer authentication (email-based login)
- Session management
- Product registration with duplicate prevention
- Composite primary key enforcement

### Project 6-5: Create Incident
- Customer login and product selection
- Only shows products customer has registered (JOIN query)
- Incident creation with title and description
- Security verification (ownership validation)

### Project 6-6: Assign Incident
- Admin interface to view unassigned incidents
- Assign incidents to available technicians
- Three-table JOIN queries
- Assignment tracking

### Project 6-7: Display Incidents (Technician Dashboard)
- Technician login and authentication
- Dashboard with statistics (open/closed/total counts)
- Filter tabs (open, closed, all incidents)
- View detailed incident information

### Project 6-8: Update Incident
- View complete incident details
- Customer contact information
- Product information
- Close incidents when resolved
- DateTime calculations (days open)
- Confirmation dialogs

---

## 🎯 Complete Workflow

### Customer Journey:
1. Login with email
2. View registered products
3. Create support incident
4. Receive incident number and confirmation

### Admin Journey:
1. View all unassigned incidents
2. See customer and product details
3. Assign incident to appropriate technician
4. Track assignment status

### Technician Journey:
1. Login to dashboard
2. View statistics (open/closed incidents)
3. Filter by status (open/closed/all)
4. View incident details
5. Contact customer (email/phone links)
6. Close incident when resolved

---

## 💾 Database Structure

**Database Name:** tech_support

**Tables:**
- `customers` - Customer information and login credentials
- `products` - Software products catalog
- `technicians` - Support staff information
- `registrations` - Customer-product relationships (composite PK)
- `incidents` - Support tickets with assignments
- `countries` - Reference data for customer locations
- `administrators` - Admin user accounts

**Key Relationships:**
- One customer can register many products (1:N)
- One product can be registered by many customers (N:M via registrations)
- One customer can create many incidents (1:N)
- One technician can be assigned many incidents (1:N)
- One incident belongs to one customer, one product, one technician (N:1:1:1)

---

## 🔧 Technologies Used

- **Backend:** PHP 8.x with PDO
- **Database:** MySQL 8.x
- **Frontend:** Bootstrap 5.3
- **Authentication:** Session-based login system
- **Security:** Prepared statements, input validation, output sanitization

---

## ✨ Key Features Implemented

### Security:
✅ PDO prepared statements (SQL injection prevention)  
✅ Input validation (FILTER_VALIDATE_EMAIL, FILTER_VALIDATE_INT)  
✅ Output sanitization (htmlspecialchars, nl2br)  
✅ Session-based authentication (separate for customers/technicians)  
✅ Ownership verification (customers can only act on their data)  
✅ Access control (technicians can only close their assigned incidents)

### Database Operations:
✅ Complex JOIN queries (2-3 table joins)  
✅ Foreign key constraints with validation  
✅ Composite primary keys  
✅ Aggregate functions (SUM, CASE statements)  
✅ NULL handling (IS NULL, IS NOT NULL)  
✅ DateTime manipulation (NOW(), diff())

### User Experience:
✅ Responsive Bootstrap design  
✅ Dynamic dropdowns populated from database  
✅ Filter tabs (open/closed/all)  
✅ Statistics dashboard  
✅ Confirmation dialogs (JavaScript)  
✅ Clickable email/phone links (mailto:, tel:)  
✅ Status badges and visual indicators  
✅ Multi-line text support (textarea, nl2br)

### Advanced Concepts:
✅ Multi-user system (3 user types)  
✅ Session management across multiple pages  
✅ Conditional queries based on URL parameters  
✅ Null coalescing operator (??)  
✅ Ternary operators for dynamic content  
✅ DateInterval calculations  
✅ Error handling with try/catch blocks

---

## 🚀 How to Run

### Prerequisites:
- XAMPP installed (Apache + MySQL)
- PHP 8.x or higher
- MySQL 8.x or higher

### Setup Instructions:

1. **Import Database:**
```
   - Open phpMyAdmin
   - Create database: tech_support
   - Import: tech_support.sql
```

2. **Start XAMPP:**
```
   - Start Apache
   - Start MySQL
```

3. **Access Application:**
```
   http://localhost/php-assignments/Assignment_4/index.php
```

### Test Credentials:

**Customer Login:**
- Email: john.smith@example.com
- (Any customer email from database)

**Technician Login:**
- Check technicians table for available emails
- (Created via Manage Technicians module)

---

## 📂 Project Structure
```
Assignment_4/
├── index.php                          # Main navigation page
├── README.md                          # This file
├── tech_support.sql                   # Database schema and sample data
├── video/                             # Demo video
│   └── demo.mp4
├── project_6_1_manage_products/
│   ├── index.php
│   ├── add_product_form.php
│   ├── add_product.php
│   ├── delete_product.php
│   ├── database.php
│   └── error.php
├── project_6_2_manage_technicians/
│   ├── index.php
│   ├── add_technician_form.php
│   ├── add_technician.php
│   ├── delete_technician.php
│   ├── database.php
│   └── error.php
├── project_6_3_manage_customers/
│   ├── select_customer.php
│   ├── view_update_customer.php
│   ├── database.php
│   └── error.php
├── project_6_4_register_product/
│   ├── customer_login.php
│   ├── register_product.php
│   ├── process_registration.php
│   ├── database.php
│   └── error.php
├── project_6_5_create_incident/
│   ├── customer_login.php
│   ├── select_product.php
│   ├── create_incident_form.php
│   ├── process_incident.php
│   ├── database.php
│   └── error.php
├── project_6_6_assign_incident/
│   ├── index.php
│   ├── assign_form.php
│   ├── process_assignment.php
│   ├── database.php
│   └── error.php
└── project_6_7_display_incidents/
    ├── technician_login.php
    ├── index.php
    ├── view_incident.php
    ├── close_incident.php
    ├── logout.php
    ├── database.php
    └── error.php
```

---

## 🎓 Learning Outcomes

This assignment demonstrates proficiency in:

- **PHP Fundamentals:** Sessions, forms, validation, error handling
- **MySQL:** Complex queries, JOINs, constraints, aggregate functions
- **Security:** Input validation, output sanitization, SQL injection prevention
- **UX Design:** Bootstrap framework, responsive design, user feedback
- **Software Architecture:** Multi-user systems, workflow design, separation of concerns
- **Version Control:** Git workflow, commit messages, repository management

---

## 📊 Statistics

- **Total Projects:** 8
- **Total PHP Files:** 30+
- **Database Tables:** 7
- **User Types:** 3 (Customer, Admin, Technician)
- **Lines of Code:** ~2000+

---

## 🔜 Future Enhancements

Potential improvements for future versions:

- Password authentication (currently email-only)
- Email notifications when incidents are assigned/closed
- Incident priority levels
- File attachments for incidents
- Technician notes/comments on incidents
- Incident history tracking
- Admin dashboard with analytics
- Search/filter functionality for all lists
- Pagination for large datasets
- Export functionality (CSV, PDF)

---

**Developer:** Rahul Kurra  
**Course:** PHP Web Development  
**Date:** February 2026  
**Assignment:** 4 - Complete Incident Management System