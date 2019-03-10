DROP USER IF EXISTS 'seadra_bd'@'localhost';
CREATE USER 'seadra_bd'@'localhost' IDENTIFIED BY 'seadra_bd';
GRANT DELETE,EXECUTE,INSERT,SELECT,UPDATE ON seadra.* TO 'seadra_bd'@'localhost';
