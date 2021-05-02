-- 初期DBを作成
-- https://qiita.com/NagaokaKenichi/items/ae037963b33a85df33f5
-- https://www.dbonline.jp/mysql/user/index1.html
-- https://www.dbonline.jp/mysql/user/index6.htmls
CREATE DATABASE IF NOT EXISTS `db_local`;
CREATE USER 'db_local'@'%' IDENTIFIED BY 'db_local';
GRANT ALL ON db_local.* TO 'db_local'@'%';

CREATE DATABASE IF NOT EXISTS `db_testing`;
CREATE USER 'db_testing'@'%' IDENTIFIED BY 'db_testing';
GRANT ALL ON db_testing.* TO 'db_testing'@'%';
