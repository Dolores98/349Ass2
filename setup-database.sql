CREATE TABLE users (
  username varchar(100) BINARY NOT NULL UNIQUE,
  password varchar(200) BINARY NOT NULL,
  isroot boolean NOT NULL,
  PRIMARY KEY (username)
);

INSERT INTO users VALUES('hello', 'kitty', 0);
INSERT INTO users VALUES('assign', 'one', 0);
INSERT INTO users VALUES('elbert', 'alcantara', 1);
INSERT INTO users VALUES('albert', 'einstein', 2);

CREATE TABLE useraccounts (
  accwebsite varchar(100) BINARY NOT NULL,
  accusername varchar(100) BINARY NOT NULL,
  accpassword varchar(200) BINARY NOT NULL,
  username varchar(100) BINARY NOT NULL,
  UNIQUE(accwebsite, accusername, accpassword, username),
  FOREIGN KEY (username) REFERENCES users(username)
);

INSERT INTO useraccounts VALUES('google', 'hello', 'there', 'hello');
INSERT INTO useraccounts VALUES('daflkl', 'sdfs', 'adf', 'hello');
INSERT INTO useraccounts VALUES('fffffff', 'aaaaaaa', 'fff', 'hello');
INSERT INTO useraccounts VALUES('fdgsfg', 'uiui', 'luilu', 'hello');
INSERT INTO useraccounts VALUES('zrddz', 'vzcvv', 'adff', 'hello');
INSERT INTO useraccounts VALUES('zrcx', 'zdrzx','fzefs', 'hello');
INSERT INTO useraccounts VALUES('radsradr', 'radsd', 'zdfdr', 'hello');
INSERT INTO useraccounts VALUES('ofaidsj', 'oihfds', 'pwodsi', 'assign');
INSERT INTO useraccounts VALUES('pweofk', 'cxjf', 'lsdk', 'assign');
INSERT INTO useraccounts VALUES('pfro', 'yh', 'okjj', 'assign');
INSERT INTO useraccounts VALUES('kljlj', 'jlk', 'afaf', 'assign');
INSERT INTO useraccounts VALUES('wrefs', 'sdzvfa', 'wqrfsdf', 'assign');
INSERT INTO useraccounts VALUES('dcsdfs', 'lkjfds', 'pfjxjv', 'assign');
INSERT INTO useraccounts VALUES('kljsns', 'jfsdl', 'pdjcdsc', 'assign');
INSERT INTO useraccounts VALUES('lkjdfs', 'newe', 'ljfsdf', 'assign');
INSERT INTO useraccounts VALUES('kljrfs', 'cz', 'af', 'assign');
INSERT INTO useraccounts VALUES('fij', 'lefs', 'lrj', 'assign'); 