create database cookspot_db;
use cookspot_db;

create table usersInfo(
	id int unsigned primary key auto_increment,
    username varchar(255) unique not null,
    displayName varchar(255) not null,
    email varchar(255) unique not null,
    authHash varchar(255) unique not null,
	picturePath varchar(255) not null,
    createdAt datetime default NOW() NOT NULL 
);

create table inactiveAccounts(
	id int unsigned primary key auto_increment,
    username varchar(255) unique not null,
    displayName varchar(255) not null,
    email varchar(255) unique not null,
    authHash varchar(255) unique not null,
    activationHash varchar(255) unique not null,
    createdAt datetime default NOW() NOT NULL,
    expirationDate datetime default NOW() NOT NULL 
);

create table emailVerifications(
	id int unsigned primary key auto_increment,
    userId int unsigned unique not null,
    newEmail varchar(255) unique not null,
    verificationHash varchar(255) unique not null,
    createdAt datetime default NOW() NOT NULL,
    expirationDate datetime default NOW() NOT NULL 
);

create table passwordResetRequests(
	id int unsigned primary key auto_increment,
    userId int unsigned unique not null,
    verificationHash varchar(255) unique not null,
    createdAt datetime default NOW() NOT NULL,
    expirationDate datetime default NOW() NOT NULL 
);

create table friends(
	id int unsigned primary key auto_increment,
    userId1 int unsigned not null,
    userId2 int unsigned not null,
    createdAt datetime default NOW() NOT NULL
);

create table friendsInvitations(
	invitationId int unsigned primary key auto_increment,
    senderId int unsigned not null,
    receiverId int unsigned not null,
    createdAt datetime default NOW() NOT NULL
);

create table sharedRecipes(
	sharedItemId int unsigned primary key auto_increment,
    ownerId int unsigned not null,
    recipeId int unsigned not null,
    recipeFilePath varchar(255) not null,
    createdAt datetime default NOW() NOT NULL
);

create table usersShareInfo(
	id int unsigned primary key auto_increment,
    userId int unsigned not null,
    sharedItemId int unsigned not null,
    createdAt datetime default NOW() NOT NULL
);

create table sharedResources(
	resourceId int unsigned primary key auto_increment,
    resourceType varchar(255) not null,
    resourcePath varchar(255) not null,
    sharedItemId int unsigned not null,
    createdAt datetime default NOW() NOT NULL
);
