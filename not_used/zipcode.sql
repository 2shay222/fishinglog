create table zipcode
(
	id int not null primary key auto_increment ,
	zip varchar(20) not null ,
	city varchar(100) not null ,
	state varchar(100) not null ,
	county varchar(75) not null ,
	fips int not null ,
	areacode varchar(3) not null ,
	dst enum('Y','N') not null ,
	timezone varchar(20) not null ,
	lat varchar(25) not null ,
	lon varchar(25) not null 
);

create index idx_zipcode on zipcode ( zip(5) );



insert into zipcode ( `zip`,`city`,`state`,`county`,`fips`,`areacode`,`dst`,`timezone`,`lat`,`lon` ) 