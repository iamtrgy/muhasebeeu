<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToWriteFile;

class BunnyAdapter implements FilesystemAdapter
{
    private string $baseUrl;
    private string $storageUrl;
    private string $apiKey;
    private string $zone;

    public function __construct(array $config)
    {
        Log::debug('BunnyAdapter initialized with config', $config);
        
        // Get the API key (support both 'key' and 'api_key')
        $this->apiKey = $config['api_key'] ?? $config['key'] ?? null;
        if (!$this->apiKey) {
            throw new \RuntimeException('API key is required for Bunny CDN');
        }

        // Get the zone name (support both 'zone' and 'storage_zone_name')
        $this->zone = $config['storage_zone_name'] ?? $config['zone'] ?? null;
        if (!$this->zone) {
            throw new \RuntimeException('Storage zone name is required for Bunny CDN');
        }

        // Set up URLs
        // CDN URL for reading (b-cdn.net)
        $this->baseUrl = rtrim($config['url'], '/') . '/' . $this->zone . '/';
        // Storage URL for writing (storage.bunnycdn.com)
        $this->storageUrl = "https://storage.bunnycdn.com/" . $this->zone . '/';

        Log::debug('BunnyAdapter baseUrl: ' . $this->baseUrl);
        Log::debug('BunnyAdapter storageUrl: ' . $this->storageUrl);
    }

    protected function getHeaders(array $additionalHeaders = [])
    {
        return array_merge([
            'AccessKey' => $this->apiKey,
            'Accept' => '*/*',
        ], $additionalHeaders);
    }

    protected function getHttpClient()
    {
        return new Client([
            'base_uri' => $this->storageUrl,
            'headers' => $this->getHeaders(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function fileExists(string $path): bool
    {
        try {
            $client = $this->getHttpClient();
            $path = ltrim($path, '/');
            
            $response = $client->head($path);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function directoryExists(string $path): bool
    {
        return true; // Bunny CDN treats directories as virtual
    }

    /**
     * @inheritdoc
     */
    public function write(string $path, string $contents, Config $config): void
    {
        $this->uploadContent($path, $contents);
    }

    /**
     * @inheritdoc
     */
    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->uploadContent($path, $contents);
    }

    /**
     * Upload content to Bunny CDN
     */
    private function uploadContent(string $path, $contents): void
    {
        $client = new Client();
        
        try {
            $response = $client->put($this->storageUrl . $path, [
                'headers' => [
                    'AccessKey' => $this->apiKey,
                    'Content-Type' => 'application/octet-stream',
                ],
                'body' => $contents,
            ]);

            if ($response->getStatusCode() !== 201) {
                Log::error('BunnyAdapter writeStream failed', [
                    'path' => $path,
                    'status' => $response->getStatusCode(),
                    'response' => $response->getBody()->getContents(),
                ]);
                throw new UnableToWriteFile("Unable to write file at location: {$path}. Failed to write file stream");
            }
        } catch (\Exception $e) {
            Log::error('BunnyAdapter writeStream error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw new UnableToWriteFile("Unable to write file at location: {$path}. " . $e->getMessage());
        }
    }

    /**
     * Store the uploaded file on the disk.
     *
     * @param string $directory The directory to store the file in (often empty if $name includes path)
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $name The desired file path/name
     * @param array $options
     * @return string|false The path to the stored file or false on failure
     */
    public function putFileAs(string $directory, \Illuminate\Http\UploadedFile $file, string $name, array $options = []): string|false
    {
        try {
            $stream = fopen($file->path(), 'r');
            if ($stream === false) {
                throw new \RuntimeException('Could not open file for reading');
            }

            $this->uploadContent($name, $stream);
            
            if (is_resource($stream)) {
                fclose($stream);
            }
            return $name; // Return the path on success
        } catch (\Exception $e) {
            Log::error('BunnyAdapter putFileAs failed: ' . $e->getMessage());
            if (isset($stream) && is_resource($stream)) {
                fclose($stream);
            }
            return false; // Return false on failure
        }
    }

    /**
     * @inheritdoc
     */
    public function read(string $path): string
    {
        try {
            $response = (new Client())->get($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() !== 200) {
                Log::error('BunnyAdapter read failed', [
                    'path' => $path,
                    'status' => $response->getStatusCode()
                ]);
                throw UnableToReadFile::fromLocation($path, 'Failed to read file');
            }
            
            return (string) $response->getBody();
        } catch (\Exception $e) {
            Log::error('BunnyAdapter read error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw UnableToReadFile::fromLocation($path, $e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function readStream(string $path)
    {
        try {
            $tempStream = fopen('php://temp', 'w+');
            
            $response = (new Client())->get($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
                'stream' => true,
            ]);
            
            if ($response->getStatusCode() !== 200) {
                Log::error('BunnyAdapter readStream failed', [
                    'path' => $path,
                    'status' => $response->getStatusCode()
                ]);
                fclose($tempStream);
                throw UnableToReadFile::fromLocation($path, 'Failed to read file stream');
            }
            
            $body = $response->getBody();
            
            while (!$body->eof()) {
                fwrite($tempStream, $body->read(1024));
            }
            
            rewind($tempStream);
            
            return $tempStream;
        } catch (\Exception $e) {
            Log::error('BunnyAdapter readStream error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            if (isset($tempStream) && is_resource($tempStream)) {
                fclose($tempStream);
            }
            throw UnableToReadFile::fromLocation($path, $e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function delete(string $path): void
    {
        $client = $this->getHttpClient();
        $path = ltrim($path, '/');

        try {
            $response = $client->delete($path);

            if ($response->getStatusCode() !== 200) {
                throw new UnableToDeleteFile("Unable to delete file located at: {$path}");
            }
        } catch (\Exception $e) {
            // If file doesn't exist, consider it as deleted
            if (str_contains($e->getMessage(), '404 Not Found')) {
                return;
            }
            throw new UnableToDeleteFile("Unable to delete file located at: {$path}", 0, $e);
        }
    }

    /**
     * @inheritdoc
     */
    public function deleteDirectory(string $path): void
    {
        // Delete all files in directory
        $files = $this->listContents($path, true);
        foreach ($files as $file) {
            if ($file['type'] === 'file') {
                $this->delete($file['path']);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function createDirectory(string $path, Config $config): void
    {
        // Bunny CDN doesn't require directory creation
    }

    /**
     * @inheritdoc
     */
    public function listContents(string $path, bool $deep): iterable
    {
        // Bunny CDN doesn't support directory listing
        return [];
    }

    /**
     * @inheritdoc
     */
    public function move(string $source, string $destination, Config $config): void
    {
        // Copy the file first
        $content = $this->read($source);
        $this->write($destination, $content, $config);
        
        // Then delete the original
        $this->delete($source);
    }

    /**
     * @inheritdoc
     */
    public function copy(string $source, string $destination, Config $config): void
    {
        $content = $this->read($source);
        $this->write($destination, $content, $config);
    }

    /**
     * Set visibility of file (not supported by Bunny CDN directly)
     * 
     * @inheritdoc
     */
    public function setVisibility(string $path, string $visibility): void
    {
        // Bunny CDN doesn't support visibility settings
        throw new UnableToSetVisibility("Bunny CDN does not support visibility settings");
    }

    /**
     * Get visibility of file (always returns public for Bunny CDN)
     * 
     * @inheritdoc
     */
    public function visibility(string $path): FileAttributes
    {
        // Bunny CDN doesn't support visibility settings
        return new FileAttributes($path, null, 'public');
    }

    /**
     * Get mime type of file from Bunny CDN headers
     * 
     * @inheritdoc
     */
    public function mimeType(string $path): FileAttributes
    {
        try {
            $response = (new Client())->head($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("Failed to get mime type from Bunny CDN");
            }
            
            $mimeType = $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';
            
            return new FileAttributes($path, null, null, null, $mimeType);
        } catch (\Exception $e) {
            Log::error('BunnyAdapter mimeType error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            
            // Default mime type if we can't determine it
            return new FileAttributes($path, null, null, null, 'application/octet-stream');
        }
    }

    /**
     * Get last modified time of file from Bunny CDN headers
     * 
     * @inheritdoc
     */
    public function lastModified(string $path): FileAttributes
    {
        try {
            $response = (new Client())->head($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("Failed to get last modified from Bunny CDN");
            }
            
            $lastModified = strtotime($response->getHeaderLine('Last-Modified')) ?: time();
            
            return new FileAttributes($path, null, null, $lastModified);
        } catch (\Exception $e) {
            Log::error('BunnyAdapter lastModified error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            
            // Default to current time if we can't determine it
            return new FileAttributes($path, null, null, time());
        }
    }

    /**
     * Get file size from Bunny CDN headers
     * 
     * @inheritdoc
     */
    public function fileSize(string $path): FileAttributes
    {
        try {
            $response = (new Client())->head($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("Failed to get file size from Bunny CDN");
            }
            
            $size = (int) $response->getHeaderLine('Content-Length') ?: 0;
            
            return new FileAttributes($path, $size);
        } catch (\Exception $e) {
            Log::error('BunnyAdapter fileSize error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            
            // Default to 0 if we can't determine it
            return new FileAttributes($path, 0);
        }
    }

    public function getUrl(string $path): string
    {
        return $this->baseUrl . $path;
    }

    public function upload(UploadedFile $file, string $path): bool
    {
        try {
            $fileStream = fopen($file->getRealPath(), 'r');
            
            $response = (new Client())->put($this->storageUrl . $path, [
                'headers' => [
                    'AccessKey' => $this->apiKey,
                    'Content-Type' => $file->getMimeType(),
                    'Content-Length' => $file->getSize(),
                ],
                'body' => $fileStream,
            ]);
            
            fclose($fileStream);
            
            if ($response->getStatusCode() === 201 || $response->getStatusCode() === 200) {
                return true;
            }
            
            Log::error('BunnyAdapter upload failed', [
                'path' => $path,
                'status' => $response->getStatusCode(),
                'response' => (string) $response->getBody()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('BunnyAdapter upload error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}