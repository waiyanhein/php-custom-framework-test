<?php

class FileService implements IFileService
{
    private $logService;
    private $databaseService;
    // set this public so that we can modify the path in the tests
    public $importFilePath;
    private $cachedAllFiles = [ ]; // just to cache the database result to improve the performance of getFullPath recursive function

    public function __construct(IDatabaseService $databaseService, ILogService $logService)
    {
        $this->importFilePath = dirname(__FILE__, 4) . '/import_file_structure';
        $this->logService = $logService;
        $this->databaseService = $databaseService;
    }

    // save the imported files into the database
    private function saveImportedFiles(array $files): void
    {
        $sql = "INSERT INTO files (id, path, parent_file_id) VALUES ";
        $parameters = [ ];
        foreach ($files as $index => $file) {
            $sql .= $index == 0? "(?,?,?)": ",(?,?,?)";
            $parameters[] = $file->getId();
            $parameters[] = $file->getPath();
            $parameters[] = $file->getParentFileId();
        }

        $this->databaseService->getDriver()->executeWriteQuery($sql, $parameters);
    }

    private function readImportFileLines(): array
    {
        //TODO: validate the format of the import file's content
        try {
            $lines = [ ];

            if ($file = fopen($this->importFilePath, "r")) {
                while(!feof($file)) {
                    $line = fgets($file);
                    if (trim($line)) {
                        $lines[ ] = $line;
                    }
                }
                fclose($file);
            }

            return $lines;
        } catch (Exception $e) {
            $this->logService->logError($e);

            return [ ];
        }
    }

    // TODO: finish up the function
    public function importFiles(): void
    {
        // TODO: use transaction
        // TODO: delete the existing data
        try {
            $this->databaseService->getDriver()->execute('DELETE FROM files');
            $files = [ ];
            $lines = $this->readImportFileLines();
            $processedLines = [ ];
            foreach ($lines as $key => $line) {
                $fileName = trim($line);
                $id = $key + 1;
                $leadingSpaceCount = count_leading_spaces($line);
                $parentFileId = null;
                if ($leadingSpaceCount == 0) {
                    $parentFileId = 0;
                } else {
                    // find the parent id - for sure it has a parent
                    $processedLinesInReversedOrder = array_reverse($processedLines);

                    foreach ($processedLinesInReversedOrder as $processedLine) {
                        $processedLineLeadingSpaceCount = count_leading_spaces($processedLine['line']);
                        if ($processedLineLeadingSpaceCount < $leadingSpaceCount) {
                            $parentFileId = $processedLine['id'];
                            break;
                        }
                    }
                }
                $files[] = new File($id, $fileName, $parentFileId);
                $processedLines[] = [
                    'line' => $line,
                    'id' => $id
                ];
            }

            if (count($files) > 0) {
                $this->saveImportedFiles($files);
            }
        } catch (Exception $exception) {
            $this->logService->logError($exception);
        }
    }

    // TODO: change this function back to private
    // this is used by search function search function is tested.
    public function getFullPath(File $file): string
    {
        $allFiles = $this->getAllFiles();
        $lookUp = new FullFilePathLookUp($allFiles);
        $lookUp->lookUp($file);

        return $lookUp->getFullPath();
    }

    public function search(string $keyword): array
    {
        $result = [];
        // TODO: use parameterised query to prevent SQL injection
        $rows = $this->databaseService->getDriver()->select("SELECT * FROM files WHERE path LIKE '%".$keyword."%'");
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $file = File::fromDatabaseResult($row);
                $result[] = $this->getFullPath($file);
            }
        }

        return $result;
    }

    // TODO: write test for this function
    public function getAllFiles(): array
    {
        // cache the files to improve the performance
        if (count($this->cachedAllFiles) > 0) {
            return $this->cachedAllFiles;
        }

        $files = [ ];
        $rows = $this->databaseService->getDriver()->select("SELECT * FROM files");
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $files[] = File::fromDatabaseResult($row);
            }
        }
        $this->cachedAllFiles = $files;

        return $files;
    }
}
