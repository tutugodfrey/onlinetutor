create database lecturer_app;
use lecturer_app;
create table registered_lecturers (
id int signed not null primary key auto_increment,
lastname varchar (40) not null,
firstname varchar (40) not null,
gender varchar (8) not null,
date_of_birth date not null,
title varchar (15) not null,
institution varchar (100) not null,
faculty varchar (40) not null,
department varchar(4) not null,
picture varchar (100),
agreement_status varchar (8) not null,
email varchar  (40) not null,
phone varchar (20) not null,
username varchar (20) not null unique,
password  varchar (40) not null,
security_question varchar (50) not null,
security_answer varchar (50) not null
);
create table registered_students (
id int signed not null primary key auto_increment,
matric_no varchar (40) not null,
lastname  varchar (40) not null,
firstname varchar (40) not null,
gender varchar (8) not null,
date_of_birth date not null,
title varchar (15) not null,
institution varchar (100) not null,
faculty varchar (40) not null,
department varchar (40) not null,
discipline varchar (40) not null,
picture varchar (100),
agreement_status varchar (5) not null,
email varchar (40) not null,
phone varchar (20) not null,
username varchar (20) not null unique,
password varchar (40) not null,
security_question varchar (50) not null,
security_answer varchar (50) not null
);

create table feedback (
id int signed not null primary key auto_increment,
sender int not null,
sender_type enum('lecturer', 'student') not null,
feedback_message text not null,
post_date datetime not null
)