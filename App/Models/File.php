<?php

// PHP version should be minimum 7.3
class File
{
    private $path;
    private $parentFileId;
    private $id;

    public function __construct(int $id, string $path, ?int $parentFileId = null)
    {
        $this->id = $id;
        $this->path = $path;
        $this->parentFileId = $parentFileId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParentFileId(): int
    {
        return (int)$this->parentFileId;
    }

    public static function fromDatabaseResult(array $row): File
    {
        return new File($row['id'], $row['path'], $row['parent_file_id']);
    }
}
