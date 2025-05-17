<?php

namespace App\Support;

use ZipArchive;
use Illuminate\Support\Str;

class WindowsCompatibleZip
{
    /** @var \ZipArchive */
    protected $zipFile;

    /** @var int */
    protected $fileCount = 0;

    /** @var string */
    protected $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->zipFile = new ZipArchive();

        $this->open();
    }

    public function open(): void
    {
        $result = $this->zipFile->open($this->filename, ZipArchive::CREATE);

        if ($result !== true) {
            throw new \RuntimeException("Could not open ZIP file: {$this->filename}. Error code: {$result}");
        }
    }

    public function close(): void
    {
        $this->zipFile->close();
    }

    /**
     * Add a file to the zip.
     *
     * @param string $pathToAdd
     * @param string|null $nameInZip
     * @return $this
     */
    public function add(string $pathToAdd, ?string $nameInZip = null): self
    {
        if (is_dir($pathToAdd)) {
            $this->addDirectory($pathToAdd, $nameInZip);
            return $this;
        }

        if (! file_exists($pathToAdd)) {
            return $this;
        }

        $nameInZip = $nameInZip ?? $pathToAdd;

        // Normalize path for Windows
        $nameInZip = $this->normalizePath($nameInZip);

        if (is_file($pathToAdd)) {
            $this->zipFile->addFile($pathToAdd, $nameInZip);

            // Skip compression for Windows compatibility
            $this->zipFile->setCompressionName($nameInZip, ZipArchive::CM_STORE, 0);

            $this->fileCount++;
        }

        return $this;
    }

    /**
     * Add the contents of a directory to the zip.
     *
     * @param string $pathToDirectory
     * @param string|null $nameInZip
     * @return $this
     */
    protected function addDirectory(string $pathToDirectory, ?string $nameInZip = null): self
    {
        $nameInZip = $nameInZip ?? $pathToDirectory;

        // Normalize path for Windows
        $nameInZip = $this->normalizePath($nameInZip);

        $this->zipFile->addEmptyDir($nameInZip);

        $files = new \FilesystemIterator($pathToDirectory);

        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $relativePath = $nameInZip . '/' . $file->getBasename();

            if ($file->isDir()) {
                $this->addDirectory($filePath, $relativePath);
                continue;
            }

            $this->add($filePath, $relativePath);
        }

        return $this;
    }

    /**
     * Normalize paths for Windows compatibility
     *
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        // Convert Windows backslashes to forward slashes
        $path = str_replace('\\', '/', $path);

        // Remove drive letter if present (e.g., C:/)
        $path = preg_replace('/^[A-Z]:\//i', '', $path);

        // Remove leading slashes
        $path = ltrim($path, '/');

        return $path;
    }

    /**
     * Get the number of files added to the zip.
     *
     * @return int
     */
    public function getFileCount(): int
    {
        return $this->fileCount;
    }
}
