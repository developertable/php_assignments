<?php
/**
 * Database Helper Functions
 * Common database operations used across the application
 */

// Get customer by email
function get_customer_by_email($db, $email) {
    try {
        $query = 'SELECT * FROM customers WHERE email = :email';
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $customer = $statement->fetch();
        $statement->closeCursor();
        return $customer;
    } catch (PDOException $e) {
        error_log("Database error in get_customer_by_email: " . $e->getMessage());
        return false;
    }
}

// Get technician by email
function get_technician_by_email($db, $email) {
    try {
        $query = 'SELECT * FROM technicians WHERE email = :email';
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $technician = $statement->fetch();
        $statement->closeCursor();
        return $technician;
    } catch (PDOException $e) {
        error_log("Database error in get_technician_by_email: " . $e->getMessage());
        return false;
    }
}

// Get all products
function get_all_products($db) {
    try {
        $query = 'SELECT * FROM products ORDER BY productName, version';
        $statement = $db->prepare($query);
        $statement->execute();
        $products = $statement->fetchAll();
        $statement->closeCursor();
        return $products;
    } catch (PDOException $e) {
        error_log("Database error in get_all_products: " . $e->getMessage());
        return [];
    }
}

// Get products registered by customer
function get_customer_products($db, $customer_id) {
    try {
        $query = 'SELECT p.productID, p.productCode, p.productName, p.version, r.registrationDate
                  FROM products p
                  INNER JOIN registrations r ON p.productID = r.productID
                  WHERE r.customerID = :customer_id
                  ORDER BY p.productName, p.version';
        $statement = $db->prepare($query);
        $statement->bindValue(':customer_id', $customer_id);
        $statement->execute();
        $products = $statement->fetchAll();
        $statement->closeCursor();
        return $products;
    } catch (PDOException $e) {
        error_log("Database error in get_customer_products: " . $e->getMessage());
        return [];
    }
}

// Get incidents assigned to technician
function get_technician_incidents($db, $tech_id, $filter = 'all') {
    try {
        $base_query = 'SELECT i.incidentID, i.title, i.description, i.dateOpened, i.dateClosed,
                             c.firstName AS customerFirstName, c.lastName AS customerLastName, 
                             c.email, c.phone,
                             p.productCode, p.productName, p.version
                      FROM incidents i
                      INNER JOIN customers c ON i.customerID = c.customerID
                      INNER JOIN products p ON i.productID = p.productID
                      WHERE i.techID = :tech_id';
        
        if ($filter == 'open') {
            $base_query .= ' AND i.dateClosed IS NULL ORDER BY i.dateOpened ASC';
        } elseif ($filter == 'closed') {
            $base_query .= ' AND i.dateClosed IS NOT NULL ORDER BY i.dateClosed DESC';
        } else {
            $base_query .= ' ORDER BY i.dateOpened DESC';
        }
        
        $statement = $db->prepare($base_query);
        $statement->bindValue(':tech_id', $tech_id);
        $statement->execute();
        $incidents = $statement->fetchAll();
        $statement->closeCursor();
        return $incidents;
    } catch (PDOException $e) {
        error_log("Database error in get_technician_incidents: " . $e->getMessage());
        return [];
    }
}

// Get incident counts for technician
function get_technician_incident_counts($db, $tech_id) {
    try {
        $query = 'SELECT 
                     SUM(CASE WHEN dateClosed IS NULL THEN 1 ELSE 0 END) as open_count,
                     SUM(CASE WHEN dateClosed IS NOT NULL THEN 1 ELSE 0 END) as closed_count
                  FROM incidents
                  WHERE techID = :tech_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':tech_id', $tech_id);
        $statement->execute();
        $counts = $statement->fetch();
        $statement->closeCursor();
        return [
            'open' => $counts['open_count'] ?? 0,
            'closed' => $counts['closed_count'] ?? 0,
            'total' => ($counts['open_count'] ?? 0) + ($counts['closed_count'] ?? 0)
        ];
    } catch (PDOException $e) {
        error_log("Database error in get_technician_incident_counts: " . $e->getMessage());
        return ['open' => 0, 'closed' => 0, 'total' => 0];
    }
}
?>