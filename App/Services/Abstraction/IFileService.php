<?php

interface IFileService
{
    public function importFiles(): void;

    public function search(string $keyword): array;
}
