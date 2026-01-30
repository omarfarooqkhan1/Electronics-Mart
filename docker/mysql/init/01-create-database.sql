-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS ni_drip_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant privileges to the user
GRANT ALL PRIVILEGES ON ni_drip_central.* TO 'ni_drip_user'@'%';
FLUSH PRIVILEGES;