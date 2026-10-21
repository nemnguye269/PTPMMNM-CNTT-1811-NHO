CREATE DATABASE IF NOT EXISTS giao_thong_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE giao_thong_db;

-- 1. Bảng tài khoản quản trị (có trường fullname)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL
);

-- Thêm tài khoản admin mẫu (Dùng ON DUPLICATE KEY để tránh lỗi trùng lặp)
INSERT INTO users (username, password, fullname) 
VALUES ('admin', 'admin123', 'Quản trị viên hệ thống') 
ON DUPLICATE KEY UPDATE fullname = VALUES(fullname);

-- 2. Bảng tuyến xe buýt (Khớp với cấu trúc giao diện PHP hiện tại)
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_code VARCHAR(20) NOT NULL UNIQUE,
    route_name VARCHAR(150) NOT NULL,
    start_station VARCHAR(100) NOT NULL,
    end_station VARCHAR(100) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    ticket_price INT NOT NULL,
    interval_minutes INT NOT NULL
);

-- Dữ liệu mẫu tuyến xe
INSERT INTO routes (route_code, route_name, start_station, end_station, start_time, end_time, ticket_price, interval_minutes) VALUES 
('Bus 01', 'Bến xe Yên Nghĩa - Bến xe Gia Lâm', 'Bến xe Yên Nghĩa', 'Bến xe Gia Lâm', '05:00:00', '21:00:00', 7000, 10),
('Bus 02', 'Bác Cổ - Bến xe Yên Nghĩa', 'Bác Cổ', 'Bến xe Yên Nghĩa', '05:00:00', '22:30:00', 7000, 15),
('Bus 32', 'Bến xe Giáp Bát - Nhổn', 'Bến xe Giáp Bát', 'Nhổn', '05:05:00', '22:30:00', 7000, 10)
ON DUPLICATE KEY UPDATE route_name = VALUES(route_name);

-- 3. Bảng trạm dừng (Mở rộng CSDL GTFS thu nhỏ cho bài Lab)
CREATE TABLE IF NOT EXISTS stops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT NOT NULL,
    stop_name VARCHAR(150) NOT NULL,
    stop_order INT NOT NULL,
    FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE
);

-- Dữ liệu mẫu trạm dừng cho Bus 32 (id = 3)
INSERT INTO stops (route_id, stop_name, stop_order) VALUES
(3, 'Bến xe Giáp Bát', 1),
(3, 'Đại học Bách Khoa', 2),
(3, 'Đại học Đại Nam', 3),
(3, 'Nhổn', 4)
ON DUPLICATE KEY UPDATE stop_name = VALUES(stop_name);
