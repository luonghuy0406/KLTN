Video test: [https://www.youtube.com/watch?v=TfiyxKGNRzI](https://www.youtube.com/watch?v=TfiyxKGNRzI).

		
#		GRADUATION THESIS - SMART TRAFFIC LIGHT SYSTEM
##			Nguyen Luong Huy - Bui Huynh Nam
##		       Instructor: M.I.T Nguyen Thanh Thai


- The program is written in python language.
- Use Apache webserver with MySQL database.
- Website written in PHP, Javascript, CSS, HTML.
## setup
----- Installing operating system and connection for pi ---

	1. Download the raspbian.img file
	2. Use SDcardFrmatter to format the memory card before installing
	3. Use Win32 Disk Imager to write the img file to the memory card
	4. Create the file ssh.txt (empty file) and put it in the memory card's boot to be able to use ssh
	5. Create file wpa_supplicant.conf to establish wifi connection to a fixed network
	//the following content
	country = us
	update_config = 1
	ctrl_interface = / var / run / wpa_supplicant

	network = {
	 scan_ssid = 1
	 ssid = "name's wifi"
	 psk = "password's wifi"
	}
	//
	5. Use putty to ssh and install remote desktop
		sudo apt install xrdp

----- Install opencv and related components ---

	6. Install virutal env
		python3 -m pip install virtualenv
	7. Create the environment
		python3 -m venv myenv
	8. Activate environment
		source myenv / bin / activate
	9. Install the necessary libraries (remember to activate the virtual environment and then install it):
		pip3 install opencv-python == 3.4.2.16
		pip3 install opencv-python-headless == 3.4.2.16
		pip3 install opencv-contrib-python == 3.4.2.16
		sudo apt-get update
		sudo apt-get install libhdf5-serial-dev
		sudo apt-get install libhdf5-dev
		sudo apt-get install libatlas-base-dev
		sudo apt-get install libqt4-test
		sudo apt-get install python3-pyqt
		sudo apt-get install libqtgui4
		sudo apt-get install libjasper-dev
	10. Install keras
		sudo apt-get install python3-numpy
		sudo apt-get install libblas-dev
		sudo apt-get install liblapack-dev
		sudo apt-get install python3-dev
		sudo apt-get install libatlas-base-dev
		sudo apt-get install gfortran
		sudo apt-get install python3-setuptools
		sudo apt-get install python3-scipy
		sudo apt-get update
		sudo apt-get install python3-h5py
		sudo pip3 install keras
	11. Install Tensorflow
		sudo pip3 install tensorflow == 1.14.0
	12. When running, you must Activate the virtual environment to run
		source myenv / bin / activate

--- Set the GPIO library to control the light ---

	13. Install the gpio library
		sudo apt-get install python-dev python-rpi.gpio
		sudo apt-get install python-pip
		pip freeze | grep RPi
		sudo apt-get install python-dev python-rpi.gpio
		pip install RPi.GPIO

---- Install webserver ----

	14. Updates and upgrades
		sudo apt update && sudo apt upgrade -y
	15. Install Apache2 for raspberry
		sudo apt install apache2 -y
	The index file is omitted in / var / www / html
	16. Install php
		cd / var / www / html
		sudo apt install php -y
	17. Install mysql
		sudo apt install mariadb-server php-mysql -y
		sudo service apache2 restart
	Set a password for id: root
		sudo mysql_secure_installation
	Access to mysql
		sudo mysql --user = root --password
	18. Install phpmyadmin
		sudo apt install phpmyadmin -y
		sudo phpenmod mysqli
		sudo service apache2 restart
		sudo ln -s / usr / share / phpmyadmin / var / www / html / phpmyadmin
	19. Additional settings
		ls -lh / var / www /
		sudo chown -R pi: www-data / var / www / html /
		sudo chmod -R 770 / var / www / html /
		ls -lh / var / www /
	20. Download and install "MySQL Connector"
		python -m pip install mysql-connector

---- done ----
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
