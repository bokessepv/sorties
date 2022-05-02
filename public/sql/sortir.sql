INSERT INTO `etat`(`id`, `libelle`) VALUES 
(1, 'Cree'),
(2, 'Ouverte'),
(3, 'Cloturee'),
(4, 'En cours'),
(5, 'Passée'),
(6, 'Annulée');

INSERT INTO `ville`(`id`, `nom`, `code_postal`) VALUES 
(1,'SAINT HERBLAIN','44800'),
(2,'HERBLAY','95220'),
(3,'CHERBOURG','50100');

INSERT INTO `campus`(`id`, `nom`) VALUES 
(1,'SAINT HERBLAIN'),
(2,'CHARTRES DE BRETAGNE'),
(3,'LA ROCHE SUR YON');

INSERT INTO `lieu`(`id`, `nom`, `rue`, `latitude`, `longitude`, `ville_id`) VALUES 
(1,'Piscine du centre ville','1 rue de la Fleur','50.80','70.20',1),
(2,'Bowling du centre commercial','3 rue du platane','220.17','30.09',2),
(3,"Cinema a cote de l'université André Castagne",'21 bis avenue Pierre Richard','137.29','26.018',3);