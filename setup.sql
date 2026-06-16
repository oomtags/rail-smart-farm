-- ═══════════════════════════════════════════════
--  RAIL Smart Farm — Database Setup
--  วิธีใช้: เปิด phpMyAdmin → Import → เลือกไฟล์นี้
-- ═══════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS smart_farm_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE smart_farm_db;

CREATE TABLE IF NOT EXISTS users (
  id         INT          NOT NULL AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL,
  email      VARCHAR(100) NOT NULL,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login DATETIME         NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_username (username),
  UNIQUE KEY uq_email    (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Default admin account (password: admin1234) ──
-- เปลี่ยนรหัสผ่านทันทีหลัง setup!
INSERT IGNORE INTO users (username, email, password, role) VALUES (
  'admin',
  'admin@smartfarm.local',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'admin'
);
