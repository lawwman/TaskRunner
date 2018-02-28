DROP TABLE IF EXISTS Tasks CASCADE;
DROP TABLE IF EXISTS Users CASCADE;
DROP TABLE IF EXISTS Bids CASCADE;

CREATE TABLE Users (
	user_name VARCHAR(20) PRIMARY KEY,
	user_pw VARCHAR(20) NOT NULL,
	email varchar(100) UNIQUE NOT NULL,
	contact VARCHAR(22) UNIQUE,	
	occupation VARCHAR(50) NOT NULL,
	birth_date DATE NOT NULL,

    -- we can't have someone born later than today using this interface
	CHECK(birth_date < now())
);

CREATE TABLE Tasks (
	task_id BIGINT PRIMARY KEY, -- BIGINT so that can contain more than 4 bil accounts (in case single user creates multiple accs)
	task_name VARCHAR(100), -- Task name should be short and sweet 
	task_details VARCHAR(3000), -- Details of task 
	
	createdDateTime TIMESTAMP NOT NULL DEFAULT now(), -- date that task was created
	
	creator VARCHAR(100) NOT NULL references Users,
	runner VARCHAR(100) references Users,
	
	duration_minutes INT, 
	status VARCHAR(100) DEFAULT 'not bidded',
	reward DECIMAL NOT NULL DEFAULT 0.0,	

	UNIQUE (task_name, task_id),

    CHECK ((runner IS DISTINCT FROM NULL AND status <> 'not bidded' AND status <> 'bidded') 
           OR (runner IS NOT DISTINCT FROM NULL AND (status = 'not bidded' OR status = 'bidded'))),
           
	-- 'pending completion' refers to tasks that have been accepted
	-- 'not bidded' refers to instance when there is no bidders for the current task yet.  
	-- 'terminated' refers to accepted bidder terminating the bid.
	-- 'deleted' refers to creator deleting the task
	CONSTRAINT discrete_status CHECK(status in ('completed', 'pending', 'bidded', 'not bidded', 'terminated', 'deleted')) 
);

CREATE TABLE Bids (
	bidder_name VARCHAR(20) NOT NULL REFERENCES Users,
	creator_name VARCHAR(20) NOT NULL REFERENCES Users,
    task_id BIGINT NOT NULL REFERENCES Tasks, 
	
    status VARCHAR(100) NOT NULL DEFAULT 'pending', 
	bidDateTime TIMESTAMP NOT NULL DEFAULT now(),

    CONSTRAINT discrete_status CHECK(status in ('rejected', 'pending', 'accepted')),
	PRIMARY KEY(bidder_name, creator_name, task_id)
);

COPY Users FROM '..\..\apps\demo\htdocs\sql\setup\data\users.csv' DELIMITER ',' CSV HEADER;
--COPY Users FROM '..\..\apps\demo\htdocs\sql\setup\data\tasks.csv' DELIMITER ',' CSV HEADER;
--COPY Users FROM '..\..\apps\demo\htdocs\sql\setup\data\bids.csv' DELIMITER ',' CSV HEADER;
