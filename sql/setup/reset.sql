DROP TABLE IF EXISTS Tasks CASCADE;
DROP TABLE IF EXISTS Bids CASCADE;
DROP TABLE IF EXISTS Taskees CASCADE;
DROP TABLE IF EXISTS Taskers CASCADE;
DROP TABLE IF EXISTS Skills CASCADE;
DROP TABLE IF EXISTS HasSkills CASCADE;

DROP FUNCTION IF EXISTS getTaskCursor;
DROP FUNCTION IF EXISTS getHasSkillCursor;
DROP FUNCTION IF EXISTS getSkillCursor;
DROP FUNCTION IF EXISTS getBidCursor;
DROP FUNCTION IF EXISTS getTaskeeCursor;
DROP FUNCTION IF EXISTS getTaskerCursor;
DROP FUNCTION IF EXISTS getAvailableTasker(varchar, TIMESTAMP, TIMESTAMP);

DROP SEQUENCE IF EXISTS idGen;

DROP FUNCTION IF EXISTS getUniqueTaskId;

-- SQL Functions to be used in the database 
CREATE SEQUENCE idGen
START WITH 1
INCREMENT BY 1
MINVALUE 1
NO MAXVALUE
CACHE 1;

CREATE FUNCTION getUniqueTaskId() RETURNS BIGINT AS $$
DECLARE 
	num BIGINT := nextval('idGen');
BEGIN
	CREATE VIEW TaskId AS SELECT task_id FROM Tasks;
    LOOP
        EXIT WHEN NOT EXISTS(SELECT task_id FROM TaskId WHERE task_id = num);
        num = nextval('idGen');
    END LOOP;
    DROP VIEW TaskId;
    RETURN num;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION getTaskCursor(refcursor) RETURNS refcursor AS $$
BEGIN
	OPEN $1 FOR SELECT * FROM Tasks;
    RETURN $1;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION getBidCursor(refcursor) RETURNS refcursor AS $$
BEGIN
	OPEN $1 FOR SELECT * FROM Bids;
    RETURN $1;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION getHasSkillCursor(refcursor) RETURNS refcursor AS $$
BEGIN
	OPEN $1 FOR SELECT * FROM HasSkills;
    RETURN $1;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION getSkillCursor(refcursor) RETURNS refcursor AS $$
BEGIN
	OPEN $1 FOR SELECT * FROM Skills;
    RETURN $1;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION getTaskeeCursor(refcursor) RETURNS refcursor AS $$
BEGIN
	OPEN $1 FOR SELECT * FROM Taskees;
    RETURN $1;
END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION getTaskerCursor(refcursor) RETURNS refcursor AS $$
BEGIN
	OPEN $1 FOR SELECT * FROM Taskers;
    RETURN $1;
END;
$$ LANGUAGE plpgsql;

CREATE TABLE Taskees (
    email VARCHAR(100) PRIMARY KEY,
    
    firstName VARCHAR(30) NOT NULL,
   	lastName VARCHAR(30) NOT NULL,
    pword TEXT NOT NULL,
    
    phone VARCHAR(22),
    
    creditNum BIGINT NOT NULL,
    creditSecurity INT NOT NULL,
    creditExpiry DATE NOT NULL,
    
    zipcode INT NOT NULL,

    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    isStaff BOOLEAN NOT NULL DEFAULT FALSE 
    CHECK (
        (NOT isAdmin AND NOT isStaff) OR 
        (NOT isAdmin AND isStaff) OR 
        (isAdmin AND isStaff)
    )
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
    
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE,
    isStaff BOOLEAN NOT NULL DEFAULT FALSE 
    
    -- we can't have someone born later than today using this interface
	CHECK(birthDate < now() AND
        ((NOT isAdmin AND NOT isStaff) OR 
        (NOT isAdmin AND isStaff) OR 
        (isAdmin AND isStaff))
    )
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
	task_id BIGINT PRIMARY KEY DEFAULT getUniqueTaskid(), -- BIGINT so that can contain more than 4 bil accounts (in case single user creates multiple accs)
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
	task_id BIGINT NOT NULL REFERENCES Tasks, 
    taskeeEmail VARCHAR(100) NOT NULL REFERENCES Taskees,
    taskerEmail VARCHAR(100) NOT NULL REFERENCES Taskers,
	
  	status VARCHAR(100) NOT NULL DEFAULT 'pending', 
	bidDateTime TIMESTAMP NOT NULL DEFAULT now(),

  	CONSTRAINT discrete_status CHECK(status in ('rejected', 'pending', 'accepted')),
	PRIMARY KEY(taskerEmail, taskeeEmail, task_id)
);

--Arguments are Skill name, task startdate and task enddate (in order)
CREATE FUNCTION getAvailableTasker(VARCHAR, TIMESTAMP, TIMESTAMP) RETURNS VARCHAR AS $$
select t.email from Taskers t inner join hasSkills HS on HS.tEmail = t.email where not exists
(select * from tasks t2 where t2.taskeremail = t.email and t2.status = 'pending' and t2.enddatetime > $2 and t2.startdatetime < $3)
and HS.sname = $1 ORDER BY HS.profLevel, HS.hrate ASC;
$$ LANGUAGE SQL;


COPY Skills FROM '..\..\apps\demo\htdocs\sql\setup\data\skills.csv' DELIMITER ',' CSV HEADER;
COPY Taskees FROM '..\..\apps\demo\htdocs\sql\setup\data\taskees.csv' DELIMITER ',' CSV HEADER;
COPY Taskers FROM '..\..\apps\demo\htdocs\sql\setup\data\taskers.csv' DELIMITER ',' CSV HEADER;
COPY HasSkills FROM '..\..\apps\demo\htdocs\sql\setup\data\hasskills.csv' DELIMITER ',' CSV HEADER;
COPY Tasks FROM '..\..\apps\demo\htdocs\sql\setup\data\tasks.csv' DELIMITER ',' CSV HEADER;
COPY Bids FROM '..\..\apps\demo\htdocs\sql\setup\data\bids.csv' DELIMITER ',' CSV HEADER;
