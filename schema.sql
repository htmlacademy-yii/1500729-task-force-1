DROP DATABASE taskforce;

CREATE DATABASE taskforce
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE UTF8_GENERAL_CI;

USE taskforce;

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    path VARCHAR(128) NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR (64) NOT NULL,
    avatar_id INT,
    information TEXT(500),
    birthday DATE,
    location_id INT,
    password VARCHAR(60) NOT NULL,
    phone VARCHAR (32),
    skype VARCHAR (32),
    telegram VARCHAR (32),
    dt_last_activity DATETIME,
    show_profile TINYINT DEFAULT 1,
    show_contacts TINYINT DEFAULT 0,
    notice_new_message TINYINT DEFAULT 1,
    notice_new_action TINYINT DEFAULT 1,
    notice_new_review TINYINT DEFAULT 1,
    failed_tasks INT,
    done_tasks INT,
    role TINYINT DEFAULT 0,
    FOREIGN KEY (location_id) REFERENCES locations (id),
    FOREIGN KEY (avatar_id) REFERENCES files (id)
);

CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(64) NOT NULL
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
    title VARCHAR(128) NOT NULL,
    description VARCHAR (256) NOT NULL,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    city_id INT,
    location_id VARCHAR(64),
    budget INT,
    due_date DATETIME,
    status TINYINT DEFAULT 0,
    executor_id INT,
    FOREIGN KEY (author_id) REFERENCES users (id),
    FOREIGN KEY (executor_id) REFERENCES users (id),
    FOREIGN KEY (city_id) REFERENCES locations(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
    content VARCHAR(256),
    task_id INT NOT NULL,
    ratio INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR (32) NOT NULL UNIQUE
);

CREATE TABLE task_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT,
    task_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (file_id) REFERENCES files (id)
);

CREATE TABLE executor_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE work_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo_id INT,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (photo_id) REFERENCES files (id)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT(500) NOT NULL,
    dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
    task_id INT NOT NULL,
    mailer_id INT NOT NULL,
    recipient_id INT NOT NULL,
    message_read TINYINT DEFAULT 0,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (mailer_id) REFERENCES users (id),
    FOREIGN KEY (recipient_id) REFERENCES users (id)
);

CREATE TABLE responds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_add DATETIME DEFAULT CURRENT_TIMESTAMP,
    budget INT NOT NULL,
    content VARCHAR(256),
    task_id INT NOT NULL,
    executor_id INT NOT NULL,
    FOREIGN KEY (task_id) REFERENCES tasks (id),
    FOREIGN KEY (executor_id) REFERENCES users(id)
);
