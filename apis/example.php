<?php
// Start session for token storage
session_start();

// Include the API handler
require_once 'apis/ApiHandler.php';

// Initialize API handler
$api = new ApiHandler();

// Example 1: Login and get token
try {
    $credentials = [
        'email' => 'user@example.com',
        'password' => 'password123'
    ];
    
    $loginResponse = $api->getToken($credentials);
    
    if ($loginResponse) {
        echo "Login successful! Token stored.";
    }
} catch (Exception $e) {
    echo "Login failed: " . $e->getMessage();
}

// Example 2: Get users list (with authentication)
try {
    $users = $api->get('users', 'list', ['page' => 1, 'limit' => 10]);
    
    if (isset($users['success']) && $users['success']) {
        // Process users data
        print_r($users['data']);
    }
} catch (Exception $e) {
    echo "Error fetching users: " . $e->getMessage();
}

// Example 3: Create new product
try {
    $productData = [
        'name' => 'New Product',
        'price' => 99.99,
        'description' => 'Product description'
    ];
    
    $newProduct = $api->post('products', 'create', $productData, 'json');
    
    if (isset($newProduct['success']) && $newProduct['success']) {
        echo "Product created successfully!";
    }
} catch (Exception $e) {
    echo "Error creating product: " . $e->getMessage();
}

// Example 4: Load data before page rendering
try {
    // This will be processed before rendering the page
    $products = $api->loadData('products', 'list', ['featured' => true]);
    
    if ($products['success']) {
        // Use the data in your page
        $featuredProducts = $products['data'];
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}

// Example 5: File upload
try {
    $uploadResponse = $api->uploadFile(
        'products', 
        'create', 
        '/path/to/image.jpg',
        ['name' => 'Product with image']
    );
    
    if ($uploadResponse['success']) {
        echo "File uploaded successfully!";
    }
} catch (Exception $e) {
    echo "Upload failed: " . $e->getMessage();
}
?>