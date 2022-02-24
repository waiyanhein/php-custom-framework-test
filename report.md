# Report

### Building up the custom re-usable PHP framework rather than organising code into class files

Before I started implementing the project, I had to think about how I can make the code the most reusable without using any third-party frameworks. The code also needs to be testable as I was planning to write unit tests on my code. So I decided to create my own PHP framework that is fully extensible and customisable without using any third-party libraries. My custom framework also includes the unit-testing feature. Inside the `App` folder sits business logic and domain logic and most of the application logic. All the unit tests and integration tests sit in the `Tests` folder. After we create a test class, we have to register the class in the `test.php` file as a new element of the `$unitTests` array. Another requirement is that all the test methods need to start with the "test" keyword.
As my framework supports the unit testing feature, I need to ensure that the classes are loosely coupled to each other and unit-testable by mocking them when needed. So I created my own IoC container which binds the abstraction classes with the concrete classes which provide the actual implementation of the application logic. You can find the IoC container class in the Container.php file which sits inside the project root folder.
The abstraction layer classes lie in the `App/Services/Abstraction` folder and the concrete classes which implement the respective interfaces are inside the `App/Services/Concrete` folder. Since the concrete classes are implementing the interfaces in the `App/Services/Abstraction` folder, we can provide the different implementations for the tests injecting the mock versions of the classes leveraging the Container class. The framework also supports `.env` file so that we can store the config variables and credentials in the `.env`. For the unit tests, it is using `.env.test` instead so that we can use the different values for the tests. The framework does not yet have its own routing system yet. When we access the project in the browser through `localhost`, it will always serve the content of the index.php in the root folder. Now it is not blocking any direct public access to other files in the project. We can do so by using `.htaccess` file in the future. My goal was to build an extensible and re-usable framework. But it was impossible to build a solid framework within the timeframe I have got. There are still rooms for improvement and features to be added.
If I was given enough time in the future, I can turn this into a solid PHP framework.


Building the custom framework is more adaptable to the change rather than organising the code into the class files and invoking the methods of the objects in sequential order. For instance, the project is now using the PDO MySQL for the database. If we want to use a different database engine let's say Postgres in the future, we can just provide the implementation for the `App\Services\Concrete\DatabasePostgresDriver.php` class and change the `DB_DRIVER` variable value in the `.env` file without modifying the rest of the project. If we are not using the framework but invoking the methods of the objects sequentially, we will have to change every single occurrence/ usage/ instance of the database object all over the project. Using this approach also enables us to use different database engines for different environments. For example, we can set different database drivers for `.env` and `.env.test` files. If we want to roll back to using MySQL database, we can just easily switch back to it by simply updating the environment variables. Therefore, using a framework provides better flexibility, adaptability and maintainability.

### Database Design

There are different ways to structure the database to store the imported files. So I compared the following two approaches.
####First Approach
The first approach is to create a table for files called, `files` with `id` and `full_path` columns. For example, it will store the full paths in the `full_path` columns in the following format when the files are imported into the database.
- C:\Documents\Images
- C:\Documents\Images\Images1.png
- C:\Documents\Images\Images2.png

This approach will make the process of printing out the results a lot easier but in the long run, it is not very extensible and efficient as the columns are not atomic. For example, in the future, if we are to add a GUI where we can rename the file paths and when we update a file path, we also have to explicitly update the file paths of the other related records. Therefore I chose the design in the second approach.

#### Second Approach
This approach also uses a single table to store the files. This is the approach I used for the project. I created a database table called, `files` to store the imported files with 3 columns, `id`, `path` and `parent_file_id`. The `path` column is used to store the single file or folder name, for example, "C:\", "Documents" and so on. If the file or folder is inside another folder, it will store the parent folder in the `parent_file_id` which is the `id` value of the parent file (folder in this case). If we want to get the full path of the file, we can keep recursively looking up the parent folder using `parent_file_id`. This approach will also resolve the problem we might have in the future that the first approach could not resolve efficiently.


### Function/ Algorithm to import the file

For importing the files, it will read the content of the file line by line. We process the data line by line and then store all the files into the database by just running a batch insert SQL statement. Within the loop, if the line does not have any leading spaces, it will consider the file as the root folder or file. If the line has leading spaces, it will not be considered as the root folder so further operation is required.
At the end of each loop, we store the line in the `$processedLines` which holds all the lines that are already processed.
It further processes the files that have leading spaces as follow.
It will look for the parent folder by looking up the `$processedLines` array variable. It will loop through the `$processedLines` array in the reverse order. Then it considers the first element with the fewer leading spaces as the parent folder.
By using this approach, we are avoiding the complexity of recursively looking up the child files or folders for each file using a recursive function.

### Additional Note
There are still many rooms for improvement and features to be implemented.
If I was given enough time in the future, I could turn this into a solid MVC PHP framework.

