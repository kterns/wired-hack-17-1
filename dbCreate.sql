Create table users(
    uid int NOT NULL AUTO_INCREMENT,
    email varchar(50) UNIQUE,
    firstName varchar(20) NOT NULL,
    lastName varchar(20) NOT NULL,
    address1 varchar(80) NOT NULL,
    address2 varchar(80) NOT NULL,
    city varchar(45) NOT NULL,
    state char(2) NOT NULL,
    zipcode char(5) NOT NULL,
    hash char(64) NOT NULL,
    salt char(64) NOT NULL,
    isGiver tinyint(1) NOT NULL,
    bio varchar(500) NOT NULL,
    pathToPicture varchar(50) NOT NULL,
    phone varchar(12) NOT NULL,
    Primary key(uid,email)
) Engine=InnoDb DEFAULT CHARSET=latin1;

Create table loggedIn(
    lid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    uid int NOT NULL UNIQUE,
    ts timestamp NOT NULL default current_timestamp,
    token char(50) NOT NULL,
    foreign key (uid) references users(uid)
) Engine=InnoDb DEFAULT CHARSET=latin1;

create table services(
    sid int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(50) NOT NULL UNIQUE
)Engine=InnoDb DEFAULT CHARSET=latin1;

create table servicesList(
    slid int not null AUTO_INCREMENT PRIMARY KEY,
    uid int not null,
    sid int not null,
    unique key (uid, sid),
    foreign key (uid) references users(uid),
    foreign key (sid) references services(sid)
)Engine=InnoDb DEFAULT CHARSET=latin1;

create table helpRequests( 
    hrid int not null auto_increment primary key,
    uid int not null,
    sid int not null,
    created timestamp NOT NULL default current_timestamp,
    description varchar(400) NOT NULL,
    title varchar(25) NOT NULL,
    isActive tinyint(1) default 1,
    foreign key (uid) references users(uid),
    foreign key (sid) references services(sid)
)Engine=InnoDb DEFAULT CHARSET=latin1;

create table transactions(
    tid int not null auto_increment primary key,
    giverId int not null,
    helpId int not null,
    timeRequested timestamp NOT NULL default current_timestamp,
    status tinyint(1) not null,
    foreign key (giverId) references users (uid),
    foreign key (helpId) references helpRequests(hrid)
)Engine=InnoDb DEFAULT CHARSET=latin1;

create table transactionComments(
    tcid int not null auto_increment primary key, 
    tid int not null,
    uid int not null,
    comment varchar(500) not null,
    ts timestamp not null default current_timestamp,
    foreign key (tid) references transactions(tid),
    foreign key (uid) references users(uid)
)Engine=InnoDb DEFAULT CHARSET=latin1;