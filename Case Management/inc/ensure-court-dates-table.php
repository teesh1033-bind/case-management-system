<?php
/**
 * Ensures court_dates table exists (no strict FK — avoids setup errors).
 */
function ensureCourtDatesTable(PDO $pdo): bool
{
    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `court_dates` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `case_id` INT NOT NULL,
                `court_date` DATETIME NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT NULL,
                `location` VARCHAR(255) NULL,
                `status` ENUM('scheduled', 'completed', 'cancelled', 'postponed') NOT NULL DEFAULT 'scheduled',
                `created_by` INT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_court_dates_case_id` (`case_id`),
                INDEX `idx_court_dates_court_date` (`court_date`),
                INDEX `idx_court_dates_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        return true;
    } catch (PDOException $e) {
        error_log('ensureCourtDatesTable: ' . $e->getMessage());
        return false;
    }
}
