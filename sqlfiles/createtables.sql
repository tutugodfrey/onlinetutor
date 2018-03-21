
create table registered_users (
	id int signed not null primary key auto_increment,
	matric_no varchar (40) not null,
	lastname  varchar (40) not null,
	firstname varchar (40) not null,
	gender varchar (8) not null,
	birthday date not null,
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
	security_answer varchar (50) not null,
	user_type enum('lecturer', 'student') not null
);

create table feedback (
	id int signed not null primary key auto_increment,
	sender int not null,
	feedback_message text not null,
	post_date datetime not null
);

create table friends (
	id int not null primary key auto_increment, 
	friend_id int not null,
	requestor_id int not null,
	confirm enum('yes', 'no'), 
	user_type varchar(10)
);

create table chats (
	chat_id int not null primary key auto_increment, 
	sender int not null, 
	receiver int not null, 
	chat_text text not null, 
	media_url varchar(120), 
	post_date datetime not null
);

create table students (
	id int not null primary key auto_increment, 
	lec_id int not null,
	student_id int not null, 
	confirm enum('yes', 'no')
);

create table courses (
	course_id tinyint not null primary key auto_increment,
	lec_id int not null,
	course_code varchar(15) not null, 
	course_title varchar(100) not null, 
	course_description text, 
	unit tinyint not null
);

create table registered_courses (
	id int not null primary key auto_increment,
	lec_id int not null, 
	student_id int not null,  
	course_id tinyint not null, 
	course_status varchar (15) not null
);

create table tests (
	id tinyint not null primary key auto_increment,
	lec_id int not null,
	test_id tinyint, 
	course_id tinyint not null, 
	duration time not null, 
	deadline datetime,  
	mark tinyint not null, 
	no_of_questions tinyint not null, 
	test_type enum('test', 'exam') not null, 
	test_status enum('opened', 'closed') not null
);

create table questions (
	question_id tinyint not null primary key auto_increment,
	lec_id int not null,
	course_id tinyint not null, 
	test_id tinyint not null, 
	questions text not null, 
	option_a varchar (100), 
	option_b varchar (100), 
	option_c varchar (100), 
	option_d varchar (100), 
	correct_option enum('a', 'b', 'c', 'd') 
);

create table score_board (
	score_id int not null primary key auto_increment,
	lec_id int not null,
	student_id int not null, 
	course_id tinyint not null,  
	test_id tinyint, 
	discussion_id tinyint, 
	score tinyint not null, 
	score_type enum('test', 'exam', 'discussion') not null
);

create table discussions ( 
	discussion_id tinyint not null primary key auto_increment,
	lec_id int not null, 
	course_id tinyint not null, 
	discussion_topic text not null, 
	post_date datetime not null, 
	type enum('open', 'close') not null 
);

create table posts ( 
	post_id int not null primary key auto_increment,
	lec_id int not null,
	student_id int not null, 
	discussion_id tinyint not null, 
	course_id tinyint not null, 
	post_date datetime not null, 
	post_text text not null, 
	type enum('open', 'close') not null, 
	graded enum('yes', 'no') not null 
);

create table announcements( 
	announcement_id int not null primary key auto_increment,
	lec_id int not null,
	post_message text not null, 
	post_date datetime not null
);

create table notes  (
	note_id int not null primary key auto_increment, 
	user_id int not null, 
	course_id tinyint not null, 
	title varchar (100), 
	note text not null,
	note_url varchar (200),
	note_date datetime not null
);

create table videos (
	video_id int not null primary key auto_increment,
  lec_id int not null, 
  course_id tinyint not null, 
  video_url varchar (150) not null, 
  video_name varchar  (100) not null, 
  video_caption varchar (100) not null
);
