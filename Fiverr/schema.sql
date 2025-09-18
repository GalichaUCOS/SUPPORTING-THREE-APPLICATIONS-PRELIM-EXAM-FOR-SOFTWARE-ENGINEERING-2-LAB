-- Create users table
CREATE TABLE fiverr_clone_users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT,
    is_client BOOLEAN,
    bio_description TEXT,
    display_picture TEXT,
    contact_number VARCHAR(255),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(50) DEFAULT 'freelancer'
);

-- Create categories table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create subcategories table
CREATE TABLE subcategories (
    subcategory_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    subcategory_name VARCHAR(255) NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- Create proposals table
CREATE TABLE proposals (
    proposal_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    description TEXT,
    image TEXT,
    min_price INT,
    max_price INT,
    view_count INT DEFAULT 0,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    category_id INT,
    subcategory_id INT,
    FOREIGN KEY (user_id) REFERENCES fiverr_clone_users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(subcategory_id)
);

-- Create offers table
CREATE TABLE offers (
    offer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    description TEXT,
    proposal_id INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES fiverr_clone_users(user_id),
    FOREIGN KEY (proposal_id) REFERENCES proposals(proposal_id),
    UNIQUE KEY unique_offer (user_id, proposal_id)
);
