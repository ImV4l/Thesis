-- Add reset token fields to register_sa table
-- Run this script in your MySQL database

USE student_assistant;

-- Add reset token fields to register_sa table
ALTER TABLE register_sa 
ADD COLUMN reset_token VARCHAR(255) NULL,
ADD COLUMN reset_token_expires DATETIME NULL;

-- Add index for better performance
CREATE INDEX idx_reset_token ON register_sa(reset_token);
CREATE INDEX idx_reset_token_expires ON register_sa(reset_token_expires);
