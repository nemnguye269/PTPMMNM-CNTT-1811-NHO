CREATE DATABASE IF NOT EXISTS `bus_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bus_management`;

-- Bảng tài khoản Admin
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL
);

-- Thêm tài khoản Admin mặc định (mật khẩu: 123456)
INSERT INTO `users` (`username`, `password`, `fullname`) VALUES
('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1aKx4B9W41FqfX5A6K7mQ6lQ9Nl1L2e', 'Quản trị viên');

-- Bảng tuyến xe buýt / giao thông công cộng
CREATE TABLE IF NOT EXISTS `routes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `route_code` VARCHAR(20) NOT NULL UNIQUE,
  `route_name` VARCHAR(150) NOT NULL,
  `start_station` VARCHAR(100) NOT NULL,
  `end_station` VARCHAR(100) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `ticket_price` INT NOT NULL,
  `interval_minutes` INT NOT NULL
);

-- Dữ liệu mẫu ban đầu
INSERT INTO `routes` (`route_code`, `route_name`, `start_station`, `end_station`, `start_time`, `end_time`, `ticket_price`, `interval_minutes`) VALUES
('Bus 01', 'Bến xe Yên Nghĩa - Bến xe Gia Lâm', 'Bến xe Yên Nghĩa', 'Bến xe Gia Lâm', '05:00:00', '21:00:00', 7000, 10),
('Bus 02', 'Bác Cổ - Bến xe Yên Nghĩa', 'Bác Cổ', 'Bến xe Yên Nghĩa', '05:00:00', '22:30:00', 7000, 15),
('Bus 32', 'Bến xe Giáp Bát - Nhổn', 'Bến xe Giáp Bát', 'Nhổn', '05:05:00', '22:30:00', 7000, 10);