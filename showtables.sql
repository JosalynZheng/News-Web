mysql> show tables;
+-------------------+
| Tables_in_module3 |
+-------------------+
| comments          |
| likes             |
| stories           |
| users             |
+-------------------+
4 rows in set (0.00 sec)

mysql> describe comments;
+------------+-------------+------+-----+-------------------+----------------+
| Field      | Type        | Null | Key | Default           | Extra          |
+------------+-------------+------+-----+-------------------+----------------+
| comment_id | smallint(6) | NO   | PRI | NULL              | auto_increment |
| story_id   | smallint(6) | NO   | MUL | NULL              |                |
| user_id    | smallint(6) | NO   | MUL | NULL              |                |
| comment    | text        | NO   |     | NULL              |                |
| date       | timestamp   | NO   |     | CURRENT_TIMESTAMP |                |
+------------+-------------+------+-----+-------------------+----------------+
5 rows in set (0.00 sec)

mysql> describe likes;
+------------+-------------+------+-----+---------+-------+
| Field      | Type        | Null | Key | Default | Extra |
+------------+-------------+------+-----+---------+-------+
| story_id   | smallint(6) | NO   | PRI | NULL    |       |
| user_id    | smallint(6) | NO   | PRI | NULL    |       |
| like_times | smallint(6) | NO   |     | NULL    |       |
+------------+-------------+------+-----+---------+-------+
3 rows in set (0.00 sec)

mysql> describe stories;
+----------+------------------------------------------------------+------+-----+-------------------+----------------+
| Field    | Type                                                 | Null | Key | Default           | Extra          |
+----------+------------------------------------------------------+------+-----+-------------------+----------------+
| story_id | smallint(6)                                          | NO   | PRI | NULL              | auto_increment |
| user_id  | smallint(6)                                          | NO   | MUL | NULL              |                |
| title    | varchar(50)                                          | NO   |     | NULL              |                |
| content  | text                                                 | NO   |     | NULL              |                |
| date     | timestamp                                            | NO   |     | CURRENT_TIMESTAMP |                |
| tag      | enum('SOCIAL','SCIENCE','HEALTH','POLITICS','OTHER') | NO   |     | SOCIAL            |                |
| image    | varchar(50)                                          | YES  |     | NULL              |                |
+----------+------------------------------------------------------+------+-----+-------------------+----------------+
7 rows in set (0.00 sec)

mysql> describe users;
+----------+--------------+------+-----+---------+----------------+
| Field    | Type         | Null | Key | Default | Extra          |
+----------+--------------+------+-----+---------+----------------+
| user_id  | smallint(6)  | NO   | PRI | NULL    | auto_increment |
| username | varchar(100) | NO   |     | NULL    |                |
| password | varchar(255) | NO   |     | NULL    |                |
| pic      | blob         | YES  |     | NULL    |                |
+----------+--------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)