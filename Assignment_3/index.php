<!DOCTYPE html>
<html>
<head>
    <title>SportsPro Technical Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        .section-title {
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 25px;
            font-weight: bold;
        }
        .project-card {
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .badge-custom {
            font-size: 0.85rem;
            padding: 5px 10px;
        }
        .category-admin { background-color: #dc3545; }
        .category-tech { background-color: #fd7e14; }
        .category-customer { background-color: #0d6efd; }
        .footer {
            background-color: #f8f9fa;
            padding: 30px 0;
            margin-top: 60px;
            border-top: 3px solid #667eea;
        }
    </style>
</head>
<body>
    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-3">
                        <i class="bi bi-wrench-adjustable"></i>
                        SportsPro Technical Support
                    </h1>
                    <p class="lead mb-0">
                        Sports management software for the sports enthusiast
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <img src="https://via.placeholder.com/150x150/667eea/ffffff?text=SportsPro" 
                         alt="SportsPro Logo" 
                         class="img-fluid rounded-circle border border-white border-3"
                         style="max-width: 150px;">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mb-5">
        
        <!-- Administrators Section -->
        <div class="mb-5">
            <h2 class="section-title text-danger">
                <i class="bi bi-shield-lock"></i> Administrators
            </h2>
            <div class="row g-4">
                
                <!-- Manage Products -->
                <div class="col-md-4">
                    <div class="card project-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-box-seam"></i> Manage Products
                            </h5>
                            <span class="badge category-admin badge-custom mb-3">Admin</span>
                            <p class="card-text text-muted">
                                Add, view, and delete products from the database.
                            </p>
                            <a href="project_6_1_manage_products/index.php" 
                               class="btn btn-danger w-100">
                                Access Module
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Manage Technicians -->
                <div class="col-md-4">
                    <div class="card project-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person-gear"></i> Manage Technicians
                            </h5>
                            <span class="badge category-admin badge-custom mb-3">Admin</span>
                            <p class="card-text text-muted">
                                Add, view, and delete support technicians.
                            </p>
                            <a href="project_6_2_manage_technicians/index.php" 
                               class="btn btn-danger w-100">
                                Access Module
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Manage Customers -->
                <div class="col-md-4">
                    <div class="card project-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-people"></i> Manage Customers
                            </h5>
                            <span class="badge category-admin badge-custom mb-3">Admin</span>
                            <p class="card-text text-muted">
                                Search, view, and update customer information.
                            </p>
                            <a href="project_6_3_manage_customers/select_customer.php" 
                               class="btn btn-danger w-100">
                                Access Module
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Customers Section -->
        <div class="mb-5">
            <h2 class="section-title text-primary">
                <i class="bi bi-person-circle"></i> Customers
            </h2>
            <div class="row g-4">
                
                <!-- Register Product -->
                <div class="col-md-4">
                    <div class="card project-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-clipboard-check"></i> Register Product
                            </h5>
                            <span class="badge category-customer badge-custom mb-3">Customer</span>
                            <p class="card-text text-muted">
                                Login and register products you own for support.
                            </p>
                            <a href="project_6_4_register_product/customer_login.php" 
                               class="btn btn-primary w-100">
                                Customer Login
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Create Incident (Placeholder for future) -->
                <div class="col-md-4">
                    <div class="card project-card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title text-muted">
                                <i class="bi bi-exclamation-triangle"></i> Create Incident
                            </h5>
                            <span class="badge bg-secondary badge-custom mb-3">Coming Soon</span>
                            <p class="card-text text-muted">
                                Submit support tickets for registered products.
                            </p>
                            <button class="btn btn-secondary w-100" disabled>
                                Not Available Yet
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Technicians Section (Placeholder for future) -->
        <div class="mb-5">
            <h2 class="section-title text-warning">
                <i class="bi bi-tools"></i> Technicians
            </h2>
            <div class="row g-4">
                
                <!-- Update Incident -->
                <div class="col-md-4">
                    <div class="card project-card border-secondary">
                        <div class="card-body">
                            <h5 class="card-title text-muted">
                                <i class="bi bi-pencil-square"></i> Update Incident
                            </h5>
                            <span class="badge bg-secondary badge-custom mb-3">Coming Soon</span>
                            <p class="card-text text-muted">
                                View and update assigned support incidents.
                            </p>
                            <button class="btn btn-secondary w-100" disabled>
                                Not Available Yet
                            </button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Database Information -->
        <div class="alert alert-info">
            <h5><i class="bi bi-info-circle"></i> Database Information</h5>
            <p class="mb-1"><strong>Database Name:</strong> tech_support</p>
            <p class="mb-1"><strong>Tables:</strong> customers, products, technicians, registrations, incidents, countries, administrators</p>
            <p class="mb-0"><strong>Server:</strong> localhost | <strong>User:</strong> ts_user</p>
        </div>
        
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Assignment Information</h5>
                    <p class="text-muted mb-0">
                        <strong>Course:</strong> PHP Web Development<br>
                        <strong>Assignment:</strong> 2 & 3 - Tech Support Projects<br>
                        <strong>Student:</strong> Rahul Kurra
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Completed Projects</h5>
                    <p class="text-muted mb-0">
                        ✅ Project 6-1: Manage Products<br>
                        ✅ Project 6-2: Manage Technicians<br>
                        ✅ Project 6-3: Manage Customers<br>
                        ✅ Project 6-4: Register Product<br>
                        ⏳ Project 6-5: Create Incident (Next)<br>
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center text-muted">
                <small>© 2025 SportsPro Technical Support | Built with PHP, MySQL & Bootstrap</small>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
</body>
</html>