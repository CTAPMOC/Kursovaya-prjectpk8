CREATE DATABASE IF NOT EXISTS student_surveys CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_surveys;
CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 login VARCHAR(50) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('admin','student') NOT NULL
 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE surveys (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(150) NOT NULL,
 description TEXT,
 status ENUM('Черновик','Открыт','Закрыт') NOT NULL DEFAULT 'Черновик',
 created_by INT NOT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE questions (
 id INT AUTO_INCREMENT PRIMARY KEY,
 survey_id INT NOT NULL,
 text VARCHAR(250) NOT NULL,
 FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE answers (
 id INT AUTO_INCREMENT PRIMARY KEY,
 question_id INT NOT NULL,
 user_id INT NOT NULL,
 value TINYINT NOT NULL,
 answered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
 FOREIGN KEY (user_id) REFERENCES users(id),
 UNIQUE uq_question_user (question_id,user_id),
 CHECK (value BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO users (name,login,password_hash,role) VALUES
('Администратор','admin','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','admin'),
('Студент 01','student1','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','student'),
('Студент 02','student2','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','student'),
('Студент 03','student3','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','student'),
('Студент 04','student4','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','student'),
('Студент 05','student5','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','student'),
('Студент 06','student6','$2y$10$5FSIpY.0Ou.3omjN/6wM9OBqrBZ.SJsqsKulbWCz84UbaNol3WvuG','student');
INSERT INTO surveys (title,description,status,created_by) VALUES
('Качество учебных занятий','Оцените организацию занятий по пятибалльной шкале.','Открыт','1'),
('Оснащение компьютерных кабинетов','Оцените оборудование и удобство работы.','Закрыт','1'),
('Работа библиотеки','Оцените доступность учебных материалов.','Черновик','1'),
('Организация практики','Оцените подготовку и сопровождение практики.','Открыт','1');
INSERT INTO questions (survey_id,text) VALUES
('1','Насколько понятно объясняется учебный материал?'),
('1','Насколько полезны практические задания?'),
('1','Насколько удобно составлено расписание?'),
('2','Как вы оцениваете производительность компьютеров?'),
('2','Насколько удобно организовано рабочее место?'),
('2','Насколько доступно необходимое программное обеспечение?'),
('3','Насколько удобно получать учебную литературу?'),
('3','Как вы оцениваете подбор электронных материалов?'),
('4','Насколько понятны задания практики?'),
('4','Как вы оцениваете помощь руководителя практики?');
INSERT INTO answers (question_id,user_id,value) VALUES
('1','3','4'),
('2','3','5'),
('3','3','3'),
('4','3','4'),
('5','3','5'),
('6','3','3'),
('1','4','5'),
('2','4','3'),
('3','4','4'),
('4','4','5'),
('5','4','3'),
('6','4','4');
