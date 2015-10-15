DROP TABLE IF EXISTS cars;
CREATE TABLE cars
(
  id              int unsigned NOT NULL auto_increment, 
  name            varchar(255) NOT NULL,                
  color           varchar(255) NOT NULL,                
  price           varchar(50) NOT NULL, 
             
  PRIMARY KEY     (id)
);
