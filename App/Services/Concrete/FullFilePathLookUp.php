<?php

class FullFilePathLookUp
{
    private $allFiles;
    private $result;

    public function __construct(array $allFiles)
    {
        $this->allFiles = $allFiles;
        $this->result = [ ];
    }

    public function lookUp(File $file)
    {
        if (! $file->getParentFileId()) {
            // get to the root
            $this->result[] = $file;
        } else {
            // look up the parent file
            $parentFile = null;
            foreach ($this->allFiles as $storedFile) {
                if ($storedFile->getId() == $file->getParentFileId()) {
                    $parentFile = $storedFile;
                }
            }
            if (! $parentFile) {
                // parent file always exist, otherwise, data structure is wrong in the database
                throw new LogicException('Parent file not found.');
            }
            $this->result[] = $file;
            $this->lookUp($parentFile);
        }
    }

    public function getFullPath(): string
    {
        $paths = [ ];
        $files = array_reverse($this->result);
        foreach ($files as $file) {
            $paths[] = $file->getPath();
        }

        return implode('\\', $paths);
    }
}
