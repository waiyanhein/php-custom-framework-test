<?php

interface IFileService
{
    public function importFiles(): void;

    public function getFullPath(File $file): string;

    public function search(string $keyword): array;

    public function getAllFiles(): array;
}
