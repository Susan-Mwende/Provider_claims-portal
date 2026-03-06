CREATE TABLE SESSIONS(
	sesskey VARCHAR( 64 ) NOT NULL DEFAULT '',
	expiry TIMESTAMP NOT NULL ,
	expireref VARCHAR( 250 ) DEFAULT '',
	created TIMESTAMP NOT NULL ,
	modified TIMESTAMP NOT NULL ,
	sessdata LONGTEXT DEFAULT '',
	PRIMARY KEY ( sesskey ) ,
	INDEX sess2_expiry( expiry ),
	INDEX sess2_expireref( expireref )
)
