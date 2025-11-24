CREATE DATABASE IF NOT EXISTS quizdb 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE quizdb;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','teacher') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE quizzes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  cover_image VARCHAR(255) DEFAULT NULL,
  visibility ENUM('public','private') DEFAULT 'private',
  created_by INT NOT NULL,
  time_limit INT DEFAULT NULL, -- detik, NULL = no limit
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  text TEXT NOT NULL,
  question_type ENUM('single','multiple','truefalse','typed','poll') DEFAULT 'single',
  time_limit INT DEFAULT 30,
  points INT DEFAULT 1000,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE choices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_id INT NOT NULL,
  text VARCHAR(1000) NOT NULL,
  is_correct TINYINT(1) DEFAULT 0,
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

CREATE TABLE quiz_sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  host_id INT NOT NULL,
  join_code VARCHAR(10) NOT NULL UNIQUE,
  status ENUM('waiting','in_progress','finished') DEFAULT 'waiting',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
  FOREIGN KEY (host_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE participants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id INT NOT NULL,
  user_id INT DEFAULT NULL, -- student akun OR null (guest)
  nickname VARCHAR(100) NOT NULL,
  score FLOAT DEFAULT 0,
  joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (session_id) REFERENCES quiz_sessions(id) ON DELETE CASCADE
);

CREATE TABLE submissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id INT NOT NULL,
  participant_id INT NOT NULL,
  score FLOAT DEFAULT 0,
  correct_count INT DEFAULT 0,
  wrong_count INT DEFAULT 0,
  finished_at TIMESTAMP NULL,
  FOREIGN KEY (session_id) REFERENCES quiz_sessions(id) ON DELETE CASCADE,
  FOREIGN KEY (participant_id) REFERENCES participants(id) ON DELETE CASCADE
);

CREATE TABLE submission_answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  submission_id INT NOT NULL,
  question_id INT NOT NULL,
  choice_id INT DEFAULT NULL, -- untuk single/multiple
  typed_answer TEXT DEFAULT NULL, -- untuk typed/poll
  is_correct TINYINT(1) DEFAULT 0,
  points_awarded INT DEFAULT 0,
  answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (submission_id) REFERENCES submissions(id) ON DELETE CASCADE,
  FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
  FOREIGN KEY (choice_id) REFERENCES choices(id) ON DELETE CASCADE
);

CREATE TABLE leaderboard (
  id INT AUTO_INCREMENT PRIMARY KEY,
  quiz_id INT NOT NULL,
  user_id INT NOT NULL,
  total_score FLOAT DEFAULT 0,
  attempts INT DEFAULT 1,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (quiz_id) REFERENCES quizzes(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (fullname, email, username, password, role)
VALUES
('Guru Satu', 'guru@example.com', 'teacher1', 
 '$2y$10$4mNwlZhFiylYtRla0NmrMeGnVJ3I/YeQjdrv44xi2WjMgQB04wiC6', 'teacher'),

('Siswa Satu', 'siswa@example.com', 'student1', 
 '$2y$10$4mNwlZhFiylYtRla0NmrMeGnVJ3I/YeQjdrv44xi2WjMgQB04wiC6', 'student');

-- password untuk dua akun itu: 123456
