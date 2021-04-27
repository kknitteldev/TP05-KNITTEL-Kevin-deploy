CREATE TABLE articles (
  ID        SERIAL PRIMARY KEY,
  REF       VARCHAR(8),
  name	    VARCHAR(250),
  price     FLOAT
);
INSERT INTO articles(ref, name, price)
VALUES  ('Y2LWP95M', 'Linux', 10),
        ('M75CEPTK', 'Windows', 15),
        ('75FGMDCX', 'Angular', 5),
        ('SX9BG46C', 'Talend', 0);
