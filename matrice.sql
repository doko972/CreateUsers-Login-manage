CREATE TABLE img(
   id_img INT AUTO_INCREMENT,
   image_path VARCHAR(255) NOT NULL,
   PRIMARY KEY(id_img)
);

CREATE TABLE users(
   id_users INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   email VARCHAR(150) NOT NULL,
   password VARCHAR(50) NOT NULL,
   create_time DATETIME NOT NULL,
   `role` enum('user','admin') NOT NULL DEFAULT 'user',
   id_img INT NOT NULL,
   PRIMARY KEY(id_users),
   FOREIGN KEY(id_img) REFERENCES img(id_img)
);
