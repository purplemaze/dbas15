CREATE TABLE person (
	email		text 	PRIMARY KEY,
	balance 	integer
);

CREATE TABLE seller (
	email 				text		PRIMARY KEY REFERENCES person(email),
	pickupAddress		text		NOT NULL,
	bankRountingNumber	smallint	NOT NULL,
	bankAccountNumber	bigint		NOT NULL
);

CREATE TABLE buyer (
	email				text	PRIMARY KEY REFERENCES person(email),
	deliveryAddress		text	NOT NULL
);

CREATE TABLE driver (
	driverId			text	PRIMARY KEY REFERENCES person(email),
	bankRountingNumber	smallint	NOT NULL,
	bankAccountNumber	bigint		NOT NULL
);

CREATE TABLE contract (
	contractId		serial 		PRIMARY KEY,
	priceNet		integer		NOT NULL,
	priceGross		integer,	
	sellerId		text		NOT NULL,
	buyerId			text		NOT NULL,
	openedAt		timestamp 	NOT NULL,
	opened 			boolean		NOT NULL,
	signedAt		timestamp,
	signed 			boolean		DEFAULT FALSE,
	payedAt			timestamp,
	payed 			boolean		DEFAULT FALSE,
	takenAt			timestamp,
	taken 			boolean		DEFAULT FALSE,
	driverId		text,
	completedAt		timestamp,
	completed 		boolean		DEFAULT FALSE,
	FOREIGN KEY 	(sellerId) 	REFERENCES seller(email),
	FOREIGN KEY 	(buyerId) 	REFERENCES buyer(email)	
);

CREATE TABLE package (
	packageId		serial		PRIMARY KEY,
	contractId		serial		REFERENCES contract(contractId),
	length			smallint	NOT NULL,
	weight			smallint	NOT NULL,
	width			smallint	NOT NULL,
	heigth			smallint	NOT NULL,
	droppedofAt		timestamp, 	
	dropped 		boolean		DEFAULT FALSE, 		
	pickedupAt		timestamp,
	pickedup 		boolean		DEFAULT FALSE,
	confirmedAt		timestamp,
	confirmed 		boolean		DEFAULT FALSE
);

CREATE RULE autoconfirm AS ON UPDATE TO package WHERE 
	OLD.confirmed = FALSE AND NEW.confirmed = TRUE
	DO ALSO 
	UPDATE contract SET completed = TRUE, completedAt = NOW() 
	WHERE contract.contractId = OLD.contractId AND 
		NOT EXISTS (
			SELECT * FROM package 
				WHERE package.contractId = OLD.contractId
				AND package.confirmed = FALSE 
				AND package.packageId != OLD.packageId
			);


CREATE TABLE item (
	id 				serial 		PRIMARY KEY,
	price 			integer		NOT NULL,
	description		text,
	name			text 		NOT NULL,
	sellerId 		text,
	createdAt		timestamp,
	sold			boolean		DEFAULT FALSE,
	FOREIGN KEY (sellerId) REFERENCES seller(email)
);

CREATE TABLE proposal (
	id 					serial		NOT NULL,
	buyerId				text		NOT NULL,
	FOREIGN KEY (id) REFERENCES item(id),
	FOREIGN KEY (buyerId) REFERENCES buyer(email),
	PRIMARY KEY (id, buyerId)

);