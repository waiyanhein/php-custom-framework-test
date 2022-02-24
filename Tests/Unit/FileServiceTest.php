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
}
