DROP TABLE IF EXISTS Tasks CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS Bids CASCADE;

DROP TABLE IF EXISTS Taskees CASCADE;
DROP TABLE IF EXISTS Taskers CASCADE;
DROP TABLE IF EXISTS Skills CASCADE;
DROP TABLE IF EXISTS HasSkills CASCADE;
DROP TABLE IF EXISTS Taskss CASCADE;

CREATE TABLE Taskees (
    email VARCHAR(100) PRIMARY KEY,
    
    firstName VARCHAR(30),
   	lastName VARCHAR(30),
    pword TEXT NOT NULL,
    
    creditNum INT NOT NULL,
    creditSecurity INT NOT NULL,
    creditExpiry DATE NOT NULL,
    
    zipcode INT NOT NULL
);

CREATE TABLE Taskers (
	email VARCHAR(100) PRIMARY KEY,
    
    firstName VARCHAR(30),
   	lastName VARCHAR(30),
    pword TEXT NOT NULL,
    
    birthDate DATE NOT NULL,
    phone VARCHAR(22) NOT NULL,
    
    creditNum INT NOT NULL,
    creditSecurity INT NOT NULL,
    creditExpiry DATE NOT NULL,
    streetAddr VARCHAR(100),
    zipcode INT NOT NULL,
    
    -- we can't have someone born later than today using this interface
	CHECK(birthDate < now())
);

CREATE TABLE Skills (
	sname VARCHAR(50) PRIMARY KEY,
    sdesc TEXT UNIQUE NOT NULL
);

CREATE TABLE HasSkills (
    tEmail VARCHAR(100) REFERENCES Taskers,
    sname VARCHAR(50) REFERENCES Skills,
    hrate INT NOT NULL,
	profLevel INT NOT NULL,
    PRIMARY KEY (tEmail, sname)
);

CREATE TABLE Taskss (
    createdAt DATE,
    taskeeEmail VARCHAR(100) REFERENCES Taskees,
    taskerEmail VARCHAR(100) REFERENCES Taskers,
   	
    ttype VARCHAR(50) REFERENCES Skills,
    details TEXT NOT NULL,
    loc VARCHAR(100) NOT NULL,
    duration VARCHAR(100) NOT NULL 
);

CREATE TABLE Users (
	username VARCHAR(20) PRIMARY KEY,
	user_pw TEXT NOT NULL,
	user_firstname VARCHAR(50) NOT NULL,
	user_lastname VARCHAR(50) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	contact VARCHAR(22),	
	occupation VARCHAR(50) NOT NULL,
	birth_date DATE NOT NULL,

  -- we can't have someone born later than today using this interface
	CHECK(birth_date < now())
);

CREATE TABLE Tasks (
	task_id BIGINT PRIMARY KEY, -- BIGINT so that can contain more than 4 bil accounts (in case single user creates multiple accs)
	task_name VARCHAR(100), -- Task name should be short and sweet 
	task_details VARCHAR(3000), -- Details of task 
	
	duration_minutes INT, 

	creator VARCHAR(100) NOT NULL references Users,
	runner VARCHAR(100) references Users,
	
	reward DECIMAL NOT NULL DEFAULT 0.0,
	status VARCHAR(100) DEFAULT 'not bidded',

	createdDateTime TIMESTAMP NOT NULL DEFAULT now(), -- date that task was created

	UNIQUE (task_name, task_id),

  CHECK ((runner IS DISTINCT FROM NULL AND status <> 'not bidded' AND status <> 'bidded') 
           OR (runner IS NOT DISTINCT FROM NULL AND (status = 'not bidded' OR status = 'bidded' OR status = 'deleted'))),
           
	-- 'pending completion' refers to tasks that have been accepted
	-- 'not bidded' refers to instance when there is no bidders for the current task yet.  
	-- 'terminated' refers to accepted bidder terminating the bid.
	-- 'deleted' refers to creator deleting the task
	CONSTRAINT discrete_status CHECK(status in ('completed', 'pending', 'bidded', 'not bidded', 'terminated', 'deleted')) 
);

CREATE TABLE Bids (
	task_id BIGINT NOT NULL REFERENCES Tasks, 
	bidder_name VARCHAR(20) NOT NULL REFERENCES Users,
	creator_name VARCHAR(20) NOT NULL REFERENCES Users,
	
  status VARCHAR(100) NOT NULL DEFAULT 'pending', 
	bidDateTime TIMESTAMP NOT NULL DEFAULT now(),

  CONSTRAINT discrete_status CHECK(status in ('rejected', 'pending', 'accepted')),
	PRIMARY KEY(bidder_name, creator_name, task_id)
);

COPY Users FROM '..\..\apps\demo\htdocs\sql\setup\data\users.csv' DELIMITER ',' CSV HEADER;
COPY Tasks FROM '..\..\apps\demo\htdocs\sql\setup\data\tasks.csv' DELIMITER ',' CSV HEADER;
COPY Bids FROM '..\..\apps\demo\htdocs\sql\setup\data\bids.csv' DELIMITER ',' CSV HEADER;

