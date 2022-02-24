<?php

function count_leading_spaces($str) {
    // \p{Zs} will match a whitespace character that is invisible,
    // but does take up space
    if (mb_ereg('^\p{Zs}+', $str, $regs) === false)
        return 0;
    return mb_strlen($regs[0]);
}

function is_directory($path)
{
    return ! explode('.', $path) > 1;
}

function read_import_file()
{
    $importFileName = __DIR__ . '/import_file';

    try {
        $lines = [ ];

        if ($file = fopen($importFileName, "r")) {
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
        return [ ];
    }
}

function get_child_files($files, $parentFile)
{
    $child_files = [ ];
    foreach ($files as $file) {
        if ($file['parent_file_id'] == $parentFile['id']) {
            $child_files[] = $file;
        }
    }

    return $child_files;
}

class Printer
{
    private $fileIdsAlreadyPrinted = [ ];
    private $tab = '   ';

    function print_files($files, $indentation = '')
    {
        foreach ($files as $index => $file) {
            // reset the identation when the file does not have any parent
            if ($file['parent_file_id'] == 0) {
                $indentation = '';
            }
            $childFiles = get_child_files($files, $file);

            if (! in_array($file['id'], $this->fileIdsAlreadyPrinted)) {
                echo $indentation . $file['path'] . "\n";
                $this->fileIdsAlreadyPrinted[] = $file['id']; // after printed, it should not be printed again
            }

            if (count($childFiles) > 0) {
                $indentation = $this->tab . $indentation;
                $this->print_files($childFiles, $indentation);
            }
        }
    }
}

// TODO: write a function to validate format - for now starting with space does not work
// folder name cannot include leading and trailing spaces for now

function import_files()
{
    $lines = read_import_file();
    $processedLines = [ ];
    $data = [];
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
        $data[] = [
            'id' => $id,
            'parent_file_id' => $parentFileId,
            'path' => $fileName,
        ];

        $processedLines[] = [
            'line' => $line,
            'id' => $id
        ];
    }

    return $data;
}

$data = import_files();
$printer = new Printer();
$printer->print_files($data);
