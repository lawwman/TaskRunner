DROP TABLE IF EXISTS Tasks CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS Bids CASCADE;

DROP TABLE IF EXISTS Taskees CASCADE;
DROP TABLE IF EXISTS Taskers CASCADE;
DROP TABLE IF EXISTS Skills CASCADE;
DROP TABLE IF EXISTS HasSkills CASCADE;

CREATE TABLE Taskees (
    email VARCHAR(100) PRIMARY KEY,
    
    firstName VARCHAR(30) NOT NULL,
   	lastName VARCHAR(30) NOT NULL,
    pword TEXT NOT NULL,
    
    phone VARCHAR(22),
    
    creditNum BIGINT NOT NULL,
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
    phone VARCHAR(22),
    
    creditNum BIGINT NOT NULL,
    creditSecurity INT NOT NULL,
    creditExpiry DATE NOT NULL,
    
    streetAddr VARCHAR(100),
    unitNum VARCHAR(20),
    zipcode INT NOT NULL,
    
    -- we can't have someone born later than today using this interface
	CHECK(birthDate < now())
);

CREATE TABLE Skills (
	sname VARCHAR(50) PRIMARY KEY,
    sdesc TEXT NOT NULL
);

CREATE TABLE HasSkills (
    tEmail VARCHAR(100) REFERENCES Taskers,
    sname VARCHAR(50) REFERENCES Skills,
    
    hrate INT NOT NULL,
	profLevel INT NOT NULL,
    pitch TEXT NOT NULL, 
    
    PRIMARY KEY (tEmail, sname)
);

CREATE TABLE Tasks (
	task_id VARCHAR(100) PRIMARY KEY, -- BIGINT so that can contain more than 4 bil accounts (in case single user creates multiple accs)
    ttype VARCHAR(50) NOT NULL REFERENCES Skills,
	task_details VARCHAR(3000), -- Details of task 
	
    taskeeEmail VARCHAR(100) NOT NULL REFERENCES Taskees,
    taskerEmail VARCHAR(100) REFERENCES Taskers,

	status VARCHAR(100) DEFAULT 'not bidded',

	createdDateTime TIMESTAMP NOT NULL DEFAULT now(), -- date that task was created
    startDateTime TIMESTAMP NOT NULL, -- start of task (include date and time)
    endDateTime TIMESTAMP NOT NULL, -- end of task (include date and time)

    loc VARCHAR(100) NOT NULL,

  CHECK ((taskerEmail IS DISTINCT FROM NULL AND status <> 'not bidded' AND status <> 'bidded') 
           OR (taskerEmail IS NOT DISTINCT FROM NULL AND (status = 'not bidded' OR status = 'bidded' OR status = 'deleted'))),
           
	-- 'pending completion' refers to tasks that have been accepted
	-- 'not bidded' refers to instance when there is no bidders for the current task yet.  
	-- 'terminated' refers to accepted bidder terminating the bid.
	-- 'deleted' refers to creator deleting the task
	CONSTRAINT discrete_status CHECK(status in ('completed', 'pending', 'bidded', 'not bidded', 'terminated', 'deleted')) 
);

CREATE TABLE Bids (
	task_id VARCHAR(100) NOT NULL REFERENCES Tasks, 
    taskeeEmail VARCHAR(100) NOT NULL REFERENCES Taskees,
    taskerEmail VARCHAR(100) NOT NULL REFERENCES Taskers,
	
  status VARCHAR(100) NOT NULL DEFAULT 'pending', 
	bidDateTime TIMESTAMP NOT NULL DEFAULT now(),

  CONSTRAINT discrete_status CHECK(status in ('rejected', 'pending', 'accepted')),
	PRIMARY KEY(taskerEmail, taskeeEmail, task_id)
);

COPY Skills FROM '..\..\apps\demo\htdocs\sql\setup\data\skills.csv' DELIMITER ',' CSV HEADER;
COPY Taskees FROM '..\..\apps\demo\htdocs\sql\setup\data\taskees.csv' DELIMITER ',' CSV HEADER;
COPY Taskers FROM '..\..\apps\demo\htdocs\sql\setup\data\taskers.csv' DELIMITER ',' CSV HEADER;
COPY HasSkills FROM '..\..\apps\demo\htdocs\sql\setup\data\hasskills.csv' DELIMITER ',' CSV HEADER;
COPY Tasks FROM '..\..\apps\demo\htdocs\sql\setup\data\tasks.csv' DELIMITER ',' CSV HEADER;
COPY Bids FROM '..\..\apps\demo\htdocs\sql\setup\data\bids.csv' DELIMITER ',' CSV HEADER;
