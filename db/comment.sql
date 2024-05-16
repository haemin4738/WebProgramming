CREATE TABLE comment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  board_num INT,
  user_id VARCHAR(50),
  name VARCHAR(50),
  content TEXT,
  date DATETIME
);