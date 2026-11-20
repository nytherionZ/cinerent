CREATE TABLE Users (
    id INT(10) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    dateOfBirth Date NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'Member',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO Users (id, username, email, password, gender, dateOfBirth, role) VALUES
(1, 'lauren', 'lauren@gmail.com', '$lauren123', 'Female', '1990-01-15', 'Admin'),
(2, 'boby_wong', 'boby@gmail.com', '$boby345', 'Male', '1995-06-20', 'Member'),
(3, 'alexander_jovan', 'alexander@gmail.com', '$alex567', 'male', '1998-03-10', 'Member'),
(4, 'cika_alexabeth', 'cika@gmail.com', '$cika789', 'Female', '2000-11-05', 'Member');

SELECT * FROM Users;

CREATE TABLE Genre (
    id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE
);

INSERT INTO Genre (id, name) VALUES
(1, 'Action'), 
(2, 'Drama'), 
(3, 'Comedy'), 
(4, 'Sci-Fi'),
(5, 'Horror'), 
(6, 'Romance'), 
(7, 'Thriller'), 
(8, 'Animation');

SELECT * FROM Genre;

CREATE TABLE Films (
    id INT(10) NOT NULL AUTO_INCREMENT,
    genreId INT(10),
    title VARCHAR(50),
    description VARCHAR(300),
    director VARCHAR(50),
    releaseYear INT(10),
    stock INT(10),

    PRIMARY KEY(id),
    CONSTRAINT genre_genreid_fk FOREIGN KEY(genreId) REFERENCES Genre(id)
);

INSERT INTO Films (id, genreId, title, description, director, releaseYear, stock) VALUES
(1,  4, 'Inception', 'A thief who steals secrets through dreams is given a chance to plant an idea in a target''s mind.', 'Christopher Nolan', 2010, 5),
(2,  1, 'The Dark Knight', 'Batman faces the Joker, a criminal mastermind who pushes Gotham City into chaos.', 'Christopher Nolan', 2008, 4),
(3,  2, 'Parasite', 'A poor family schemes to become employed by a wealthy household, with unexpected consequences.', 'Bong Joon-ho', 2019, 3),
(4,  5, 'Get Out', 'A young man visits his girlfriend''s family estate and uncovers a disturbing secret.', 'Jordan Peele', 2017, 3),
(5,  8, 'Spirited Away', 'A girl wanders into a world of spirits and must work in a bathhouse to save her parents.', 'Hayao Miyazaki', 2001, 6),
(6,  3, 'Superbad', 'Two high school friends try to enjoy one last wild night before graduation.', 'Greg Mottola', 2007, 2),
(7,  6, 'The Notebook', 'A young couple falls in love in the 1940s, told through an elderly man reading from a notebook.', 'Nick Cassavetes', 2004, 4),
(8,  4, 'Interstellar', 'A team of explorers travels through a wormhole in search of a new home for humanity.', 'Christopher Nolan', 2014, 5),
(9,  8, 'Toy Story', 'A cowboy doll feels threatened when a new space ranger toy becomes the favorite of their owner.', 'John Lasseter', 1995, 6),
(10, 1, 'Mad Max: Fury Road', 'In a desert wasteland, a drifter and a rebel soldier flee a tyrant across the desert.', 'George Miller', 2015, 3),
(11, 7, 'Se7en', 'Two detectives hunt a serial killer who bases his crimes on the seven deadly sins.', 'David Fincher', 1995, 3);


CREATE TABLE Rentals (
    id INT(10) AUTO_INCREMENT,
    userId INT(10),
    borrowDate DATE,
    dueDate DATE,
    returnDate DATE,
    status VARCHAR(15),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY(id),
    CONSTRAINT users_userid_fk FOREIGN KEY (userId) REFERENCES Users(id)
);

INSERT INTO Rentals (id, userId, borrowDate, dueDate, returnDate, status) VALUES
(1, 2, '2026-04-01', '2026-04-08', '2026-04-06', 'Returned'),
(2, 2, '2026-04-10', '2026-04-17', NULL, 'Active'),
(3, 3, '2026-04-05', '2026-04-12', '2026-04-11', 'Returned'),
(4, 2, '2026-04-21', '2026-04-28', NULL, 'Active');

SELECT * FROM Rentals;

CREATE TABLE RentalDetails (
    rentalId INT NOT NULL,
    filmId INT NOT NULL,
    PRIMARY KEY (rentalId, filmId),
    FOREIGN KEY (rentalId) REFERENCES Rentals(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (filmId) REFERENCES Films(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

INSERT INTO RentalDetails (rentalId, filmId) VALUES
(1, 1), (1, 2),
(2, 6), (2, 10),
(3, 3), (3, 8),
(4, 4), (4, 5);

SELECT * FROM RentalDetails;

CREATE TABLE CartItems (
    filmId INT(10),
    userId INT(10),
    addedDate DATE,
    
    PRIMARY KEY (filmId, userId),                            
    CONSTRAINT film_cart_fk
        FOREIGN KEY (filmId) REFERENCES Films(id),
    CONSTRAINT user_cart_fk
        FOREIGN KEY (userId) REFERENCES Users(id)
);

INSERT INTO CartItems (filmId, userId, addedDate) VALUES
(4, 3, '2026-11-18'),
(6, 4, '2026-11-18');




