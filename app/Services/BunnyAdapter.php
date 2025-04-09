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
use League\Flysystem\UnableToWriteFile;

class BunnyAdapter implements FilesystemAdapter
{
    protected $client;
    protected $config;
    protected $apiKey;
    protected $zone;
    protected $region;
    protected $url;
    protected $baseUrl;

    public function __construct(array $config)
    {
        Log::debug('BunnyAdapter initialized with config', $config);
        
        $this->config = $config;
        $this->apiKey = $config['key'] ?? $config['api_key'] ?? null; // Support both keys
        $this->zone = $config['zone'] ?? $config['storage_zone_name'] ?? null; // Support both keys
        $this->region = $config['region'] ?? null;
        $this->url = $config['url'] ?? null;
        
        if (!$this->apiKey || !$this->zone || !$this->url) {
            Log::error('BunnyAdapter missing required config', [
                'key' => $this->apiKey ? 'present' : 'missing',
                'zone' => $this->zone ? 'present' : 'missing',
                'url' => $this->url ? 'present' : 'missing'
            ]);
            throw new \Exception('BunnyAdapter missing required configuration');
        }
        
        $this->baseUrl = rtrim($this->url, '/') . '/' . $this->zone . '/';
        
        Log::debug('BunnyAdapter baseUrl: ' . $this->baseUrl);
        
        // Initialize the client with default config
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 30,
            'verify' => false,
            'http_errors' => false
        ]);
    }

    protected function getHeaders(array $additionalHeaders = [])
    {
        return array_merge([
            'AccessKey' => $this->apiKey,
            'Accept' => '*/*',
        ], $additionalHeaders);
    }

    /**
     * @inheritdoc
     */
    public function fileExists(string $path): bool
    {
        try {
            $response = $this->client->head($path, [
                'headers' => $this->getHeaders(),
            ]);
            
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('BunnyAdapter fileExists error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
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
        try {
            $response = $this->client->put($path, [
                'headers' => $this->getHeaders([
                    'Content-Type' => 'application/octet-stream',
                ]),
                'body' => $contents,
            ]);
            
            if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
                Log::error('BunnyAdapter write failed', [
                    'path' => $path,
                    'status' => $response->getStatusCode(),
                    'response' => (string) $response->getBody()
                ]);
                throw UnableToWriteFile::atLocation($path, 'Failed to write file');
            }
        } catch (\Exception $e) {
            Log::error('BunnyAdapter write error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw UnableToWriteFile::atLocation($path, $e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function writeStream(string $path, $contents, Config $config): void
    {
        try {
            $response = $this->client->put($path, [
                'headers' => $this->getHeaders([
                    'Content-Type' => 'application/octet-stream',
                ]),
                'body' => $contents,
            ]);
            
            if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
                Log::error('BunnyAdapter writeStream failed', [
                    'path' => $path,
                    'status' => $response->getStatusCode(),
                    'response' => (string) $response->getBody()
                ]);
                throw UnableToWriteFile::atLocation($path, 'Failed to write file stream');
            }
        } catch (\Exception $e) {
            Log::error('BunnyAdapter writeStream error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw UnableToWriteFile::atLocation($path, $e->getMessage());
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

            $this->writeStream($name, $stream, $options);
            
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
            $response = $this->client->get($path, [
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
            
            $response = $this->client->get($path, [
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
        try {
            $response = $this->client->delete($path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
                Log::error('BunnyAdapter delete failed', [
                    'path' => $path,
                    'status' => $response->getStatusCode(),
                    'response' => (string) $response->getBody()
                ]);
                throw UnableToDeleteFile::atLocation($path, 'Failed to delete file');
            }
        } catch (\Exception $e) {
            Log::error('BunnyAdapter delete error', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw UnableToDeleteFile::atLocation($path, $e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function deleteDirectory(string $path): void
    {
        // Bunny doesn't have a direct way to delete directories, so we don't actually need to do anything here
        return;
    }

    /**
     * @inheritdoc
     */
    public function createDirectory(string $path, Config $config): void
    {
        // Bunny doesn't have a direct way to create directories, so we don't actually need to do anything here
        return;
    }

    /**
     * @inheritdoc
     */
    public function listContents(string $path, bool $deep): iterable
    {
        // This is a more complex operation that would require listing files from Bunny API
        // For this simple adapter, we'll return an empty array
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
        // Bunny CDN handles visibility through Storage Zone settings
        // This operation is not applicable for BunnyAdapter
    }

    /**
     * Get visibility of file (always returns public for Bunny CDN)
     * 
     * @inheritdoc
     */
    public function visibility(string $path): FileAttributes
    {
        // Return default visibility as public
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
            $response = $this->client->head($path, [
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
            $response = $this->client->head($path, [
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
            $response = $this->client->head($path, [
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
        return $this->url . '/' . $this->zone . '/' . $path;
    }

    public function upload(UploadedFile $file, string $path): bool
    {
        try {
            $fileStream = fopen($file->getRealPath(), 'r');
            
            $response = $this->client->put($path, [
                'headers' => $this->getHeaders([
                    'Content-Type' => $file->getMimeType(),
                    'Content-Length' => $file->getSize(),
                ]),
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