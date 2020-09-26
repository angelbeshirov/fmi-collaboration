# Online Collaboration
1. Features
	- Login and register
	- Upload files
	- Download files
	- Delete files
	- Share files with other users
	- Edit files simultaniously by multiple users
	- One user can edit multiple files simultaniously
	- Only the user who uploaded the file can delete it
	- Both the uploader and the user with which the file was shared can delete the share (i.e. stop sharing the file)
	- Only. txt file extension is supported for now
2. Future goals
	- Add support for doc and docx
	- Send notifications to recepients when a file has been shared with them
	- Improve the user interface
3. Installation
	- Clone the repo
	- Make sure you have downloaded Apache server (XAMPP is a perfect match for that)
	- Move the files into the htdocs folder
	- Configure the database, using the sql scripts located in sql directory. setup.sql creates the database and the tables
	  and insert_data.sql adds the needed data so that the system can function properly. You can connect to the database either threw the
	  terminal or using the database control panel (e.g. PHPMyAdmin of XAMPP). If the host, name and possword used for connecting to the
	  database are different than the ones in config.ini in the config folder, then you need to reconfigure them.
	- Now use either Chrome, Firefox or Opera to connect to localhost.
