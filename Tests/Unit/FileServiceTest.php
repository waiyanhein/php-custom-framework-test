<?php

class FileServiceTest extends BaseTest
{
    private $fileService;

    public function beforeEach(): void
    {
        parent::beforeEach();
        // do not use the container as we are testing the concrete implementation of the class
        $this->fileService = new FileService($this->databaseService, $this->logService);
        $this->fileService->importFilePath = __DIR__ . '/import_file_structure';
    }

    public function test_import_files_import_files_func_imports_file_into_database_in_correct_structure()
    {
        // same data as in the import file but in array format
        $expectedData = [
            [
                'id' => 1,
                'path' => 'C:\\',
                'parent_file_id' => 0
            ],
            [
                'id' => 2,
                'path' => 'Documents',
                'parent_file_id' => 1
            ],
            [
                'id' => 3,
                'path' => 'Images',
                'parent_file_id' => 2
            ],
            [
                'id' => 4,
                'path' => 'Images1.jpg',
                'parent_file_id' => 3
            ],
            [
                'id' => 5,
                'path' => 'Images2.jpg',
                'parent_file_id' => 3
            ],
            [
                'id' => 6,
                'path' => 'Images3.jpg',
                'parent_file_id' => 3
            ],
            [
                'id' => 7,
                'path' => 'Works',
                'parent_file_id' => 2
            ],
            [
                'id' => 8,
                'path' => 'Letter.doc',
                'parent_file_id' => 7
            ],
            [
                'id' => 9,
                'path' => 'Accountant',
                'parent_file_id' => 7
            ],
            [
                'id' => 10,
                'path' => 'Accounting.xls',
                'parent_file_id' => 9
            ],
            [
                'id' => 11,
                'path' => 'AnnualReport.xls',
                'parent_file_id' => 9
            ],
            [
                'id' => 12,
                'path' => 'Program Files',
                'parent_file_id' => 1
            ],
            [
                'id' => 13,
                'path' => 'Skype',
                'parent_file_id' => 12
            ],
            [
                'id' => 14,
                'path' => 'Skype.exe',
                'parent_file_id' => 13
            ],
            [
                'id' => 15,
                'path' => 'Readme.txt',
                'parent_file_id' => 13
            ],
            [
                'id' => 16,
                'path' => 'Mysql',
                'parent_file_id' => 12
            ],
            [
                'id' => 17,
                'path' => 'Mysql.exe',
                'parent_file_id' => 16
            ],
            [
                'id' => 18,
                'path' => 'Mysql.com',
                'parent_file_id' => 16
            ],
        ];
        $this->fileService->importFiles();

        $rows = $this->databaseService->getDriver()->select("SELECT * FROM files");
        $this->assertEqual(count($expectedData), count($rows));
        foreach ($expectedData as $index => $expectedRow) {
            $this->assertEqual($expectedRow['id'], $rows[$index]['id']);
            $this->assertEqual($expectedRow['path'], $rows[$index]['path']);
            $this->assertEqual($expectedRow['parent_file_id'], $rows[$index]['parent_file_id']);
        }
    }

    public function test_import_files_import_files_func_clears_existing_data_before_importing_files()
    {
        // imported twice but one single import is saved
        $this->fileService->importFiles();
        $this->fileService->importFiles();
        $rows = $this->databaseService->getDriver()->select("SELECT * FROM files");

        $this->assertEqual(18, count($rows));
    }

    public function test_it_get_all_files_func_retrieves_all_files_from_database()
    {
        $this->fileService->importFiles();// just called this function to seed data for the time-being
        $result = $this->fileService->getAllFiles();
        $this->assertEqual(18, count($result));
        foreach ($result as $index => $file) {
            $expectedId = $index + 1;
            $this->assertEqual($expectedId, $file->getId());
        }
    }

    public function test_get_full_path_func_returns_full_path_recursively_looking_up_parent_directories()
    {
        $this->fileService->importFiles();// just called this function to seed data for the time-being
        $file = new File(18, "Mysql.com", 16);
        $result = $this->fileService->getFullPath($file);

        $this->assertEqual('C:\\\Program Files\Mysql\Mysql.com', $result);
    }

    public function test_get_full_path_func_returns_initial_file_path_when_initial_file_does_not_have_parent_directory()
    {
        $this->fileService->importFiles();// just called this function to seed data for the time-being
        $file = new File(1, "C:\\", 0);
        $result = $this->fileService->getFullPath($file);

        $this->assertEqual('C:\\', $result);
    }

    public function test_search_returns_full_paths_of_files_that_match_to_keyword()
    {
        $expectedResult = [
            'C:\\\Documents\Images',
            'C:\\\Documents\Images\Images1.jpg',
            'C:\\\Documents\Images\Images2.jpg',
            'C:\\\Documents\Images\Images3.jpg'
        ];
        $this->fileService->importFiles();// just called this function to seed data for the time-being
        $result = $this->fileService->search("Image");

        $this->assertEqual(count($expectedResult), count($result));
        foreach ($expectedResult as $index => $expectedPath) {
            $this->assertEqual($expectedPath, $result[$index]);
        }
    }

    public function test_search_returns_full_paths_of_all_files_when_keyword_is_empty()
    {
        $expectedResult = [
            'C:\\',
            'C:\\\Documents',
            'C:\\\Documents\Images',
            'C:\\\Documents\Images\Images1.jpg',
            'C:\\\Documents\Images\Images2.jpg',
            'C:\\\Documents\Images\Images3.jpg',
            'C:\\\Documents\Works',
            'C:\\\Documents\Works\Letter.doc',
            'C:\\\Documents\Works\Accountant',
            'C:\\\Documents\Works\Accountant\Accounting.xls',
            'C:\\\Documents\Works\Accountant\AnnualReport.xls',
            'C:\\\Program Files',
            'C:\\\Program Files\Skype',
            'C:\\\Program Files\Skype\Skype.exe',
            'C:\\\Program Files\Skype\Readme.txt',
            'C:\\\Program Files\Mysql',
            'C:\\\Program Files\Mysql\Mysql.exe',
            'C:\\\Program Files\Mysql\Mysql.com',
        ];
        $this->fileService->importFiles();// just called this function to seed data for the time-being
        $result = $this->fileService->search("");

        $this->assertEqual(count($expectedResult), count($result));
        foreach ($expectedResult as $index => $expectedPath) {
            $this->assertEqual($expectedPath, $result[$index]);
        }
    }

    public function test_search_func_returns_empty_array_when_keyword_does_not_match_any_of_the_files()
    {
        $this->fileService->importFiles();// just called this function to seed data for the time-being
        $result = $this->fileService->search("Testing");

        $this->assertEqual(0, count($result));
    }
}
