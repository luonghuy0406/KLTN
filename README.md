Video test: [https://www.youtube.com/watch?v=TfiyxKGNRzI](https://www.youtube.com/watch?v=TfiyxKGNRzI).

		
#		GRADUATION THESIS - SMART TRAFFIC LIGHT SYSTEM
##			Nguyen Luong Huy - Bui Huynh Nam
##		       Instructor: M.I.T Nguyen Thanh Thai


- The program is written in python language.
- Use Apache webserver with MySQL database.
- Website written in PHP, Javascript, CSS, HTML.


## The thesis includes the following directories:

1. The baocaochitiet directory contains the file baocaochitet.doc, which is a report file.
2. The code_detect directory contains KLTN.py file which is the main program, pretrain model MobileNet SSD and video test, has the function of detect + tracking + counting vehicle sending data to database and controlling traffic light.
3. The code_webserver directory contains php, html, js, sql files and images to display and show the traffic flow chart on the web.
4. The database directory contains the database.sql file
5. The setup directory contains the setup.txt file, which provides instructions on installing the environment and the necessary libraries.

## Instructions to run the file:

1. Read the setup.txt file in the setup directory to install the environment you need to run the program.
2. Open the terminal
3. Activate the virtual environment with the command "source myenv / bin / activate" "
4. Access the directory containing the KLTN.py file with the command "cdc / home / pi / the directory name containing the file"
5. Enter the command "python KLTN.py" to run the program.
6. To turn off the program while it is running, click the video window and press the Esc key.
7. To see the website showing the graph: access the ip address of raspberry pi.
ex: 172.20.10.2:80
8. To access phpmyadmin: access the ip address of raspberry pi / phpmyadmin
ex: 172.20.10.2/phpmyadmin
