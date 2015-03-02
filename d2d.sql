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
	opendAt			timestamp 	NOT NULL,
	opend 			boolean		NOT NULL,
	signedAt		timestamp,
	signed 			boolean,
	payedAt			timestamp,
	payed 			boolean,
	takenAt			timestamp,
	taken 			boolean,
	driverId		text,
	completedAt		timestamp,
	colpleted 		boolean,
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
	price 			integer		NOT NULL,
	droppedofAt		timestamp, 	
	dropped 		boolean, 		
	pickedupAt		timestamp,
	pickedup 		boolean,
	confirmedAt		timestamp,
	confirmed 		boolean
);

CREATE TABLE item (
	id 				serial 		PRIMARY KEY,
	price 			integer		NOT NULL,
	description		text,
	name			text 		NOT NULL,
	sellerId 		text,
	FOREIGN KEY (sellerId) REFERENCES seller(email)
);

CREATE TABLE proposal (
	id 					serial		NOT NULL,
	buyerId				text		NOT NULL,		
	deliveryAddress		text		NOT NULL,
	FOREIGN KEY (id) REFERENCES item(id),
	FOREIGN KEY (buyerId) REFERENCES buyer(email),
	PRIMARY KEY (id, buyerId)

);