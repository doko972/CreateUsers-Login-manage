CREATE TABLE img(
   id_img INT AUTO_INCREMENT,
   PRIMARY KEY(id_img)
);

CREATE TABLE users(
   id_users INT AUTO_INCREMENT,
   name VARCHAR(50) NOT NULL,
   email VARCHAR(150) NOT NULL,
   password VARCHAR(50) NOT NULL,
   create_time DATETIME NOT NULL,
   id_img INT NOT NULL,
   PRIMARY KEY(id_users),
   FOREIGN KEY(id_img) REFERENCES img(id_img)
);

CREATE TABLE manage(
   id_users_user INT,
   id_users_admin INT,
   PRIMARY KEY(id_users_user, id_users_admin),
   FOREIGN KEY(id_users_user) REFERENCES users(id_users),
   FOREIGN KEY(id_users_admin) REFERENCES users(id_users)
);
