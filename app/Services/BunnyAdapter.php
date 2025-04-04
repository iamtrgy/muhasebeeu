<?php

namespace App\Services;

use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\DirectoryAttributes;
use GuzzleHttp\Client;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToReadFile;

class BunnyAdapter implements FilesystemAdapter
{
    protected $client;
    protected $storageZone;
    protected $apiKey;
    protected $region;
    protected $hostname;
    protected $baseUrl;

    public function __construct(string $storageZone, string $apiKey, string $region = 'de', ?string $hostname = null)
    {
        $this->storageZone = $storageZone;
        $this->apiKey = $apiKey;
        $this->region = $region;
        $this->hostname = $hostname ?? "storage.bunnycdn.com";
        $this->baseUrl = "https://{$this->hostname}/{$this->storageZone}/";
        
        // Initialize the client without default headers - we'll add them per request
        $this->client = new Client([
            'http_errors' => false, // Don't throw exceptions for HTTP errors
        ]);
    }

    /**
     * Get the standard headers required for most API calls
     */
    protected function getHeaders(array $additionalHeaders = [])
    {
        return array_merge([
            'AccessKey' => $this->apiKey,
            'Accept' => '*/*',
        ], $additionalHeaders);
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            // Add debugging
            \Log::debug("BunnyAdapter::write - Starting upload", [
                'path' => $path,
                'size' => strlen($contents),
                'base_url' => $this->baseUrl
            ]);
            
            $response = $this->client->put($this->baseUrl . $path, [
                'headers' => $this->getHeaders([
                    'Content-Type' => 'application/octet-stream',
                ]),
                'body' => $contents,
            ]);
            
            $statusCode = $response->getStatusCode();
            $reasonPhrase = $response->getReasonPhrase();
            
            // Add response logging
            \Log::debug("BunnyAdapter::write - Got response", [
                'status_code' => $statusCode,
                'reason' => $reasonPhrase,
                'success' => ($statusCode < 400)
            ]);
            
            if ($statusCode >= 400) {
                $responseBody = (string) $response->getBody();
                \Log::error("BunnyAdapter::write - Failed upload", [
                    'status_code' => $statusCode,
                    'reason' => $reasonPhrase,
                    'response' => $responseBody
                ]);
                throw new \Exception("BunnyStorage upload failed: " . $statusCode . ' ' . $reasonPhrase . ' - ' . $responseBody);
            }
            
            // Successfully wrote the file - no return value needed (void)
            \Log::debug("BunnyAdapter::write - Upload successful");
        } catch (\Exception $e) {
            \Log::error("BunnyAdapter::write - Exception", [
                'message' => $e->getMessage(),
                'class' => get_class($e)
            ]);
            throw UnableToWriteFile::atLocation($path, $e->getMessage());
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        try {
            $response = $this->client->put($this->baseUrl . $path, [
                'headers' => $this->getHeaders([
                    'Content-Type' => 'application/octet-stream',
                ]),
                'body' => $contents,
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage upload failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage());
        }
    }

    public function read(string $path): string
    {
        try {
            $response = $this->client->get($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage read failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
            
            return (string) $response->getBody();
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage());
        }
    }

    public function readStream(string $path)
    {
        try {
            $response = $this->client->get($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
                'stream' => true,
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage read stream failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
            
            return $response->getBody()->detach();
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage());
        }
    }

    public function delete(string $path): void
    {
        try {
            $response = $this->client->delete($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400 && $response->getStatusCode() !== 404) {
                throw new \Exception("BunnyStorage delete failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage());
        }
    }

    public function deleteDirectory(string $path): void
    {
        try {
            // Ensure path ends with slash for directories
            $path = rtrim($path, '/') . '/';
            
            $response = $this->client->delete($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400 && $response->getStatusCode() !== 404) {
                throw new \Exception("BunnyStorage delete directory failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
        } catch (\Exception $e) {
            throw new \Exception("Failed to delete directory: " . $e->getMessage());
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        // Bunny.net doesn't require explicit directory creation
        // Directories are created automatically when files are uploaded
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // Bunny.net handles visibility through Storage Zone settings
        // This operation is not applicable for BunnyStorage
    }

    public function visibility(string $path): FileAttributes
    {
        // Return default visibility as public
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        try {
            $response = $this->client->head($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage mimeType check failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
            
            return new FileAttributes(
                $path, 
                null, 
                null, 
                null, 
                $response->getHeaderLine('Content-Type')
            );
        } catch (\Exception $e) {
            throw new \Exception("Failed to get mime type: " . $e->getMessage());
        }
    }

    public function lastModified(string $path): FileAttributes
    {
        try {
            $response = $this->client->head($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage lastModified check failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
            
            return new FileAttributes(
                $path, 
                null, 
                null, 
                strtotime($response->getHeaderLine('Last-Modified'))
            );
        } catch (\Exception $e) {
            throw new \Exception("Failed to get last modified: " . $e->getMessage());
        }
    }

    public function fileSize(string $path): FileAttributes
    {
        try {
            $response = $this->client->head($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage fileSize check failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
            
            return new FileAttributes(
                $path, 
                (int) $response->getHeaderLine('Content-Length')
            );
        } catch (\Exception $e) {
            throw new \Exception("Failed to get file size: " . $e->getMessage());
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        try {
            // Ensure path ends with slash for directories
            $path = rtrim($path, '/');
            if (!empty($path)) {
                $path .= '/';
            }
            
            $response = $this->client->get($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            if ($response->getStatusCode() >= 400) {
                throw new \Exception("BunnyStorage listContents failed: " . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
            }
            
            $contents = json_decode((string) $response->getBody(), true);
            
            if (!is_array($contents)) {
                return [];
            }

            foreach ($contents as $item) {
                if ($item['IsDirectory']) {
                    yield new DirectoryAttributes(
                        trim($path . $item['ObjectName'], '/'),
                        null,
                        strtotime($item['LastChanged'])
                    );
                    
                    // If deep listing is requested and this is a directory
                    if ($deep) {
                        $subPath = trim($path . $item['ObjectName'], '/');
                        $subItems = $this->listContents($subPath, $deep);
                        foreach ($subItems as $subItem) {
                            yield $subItem;
                        }
                    }
                } else {
                    yield new FileAttributes(
                        trim($path . $item['ObjectName'], '/'),
                        $item['Length'],
                        null,
                        strtotime($item['LastChanged']),
                        $item['ContentType'] ?? null
                    );
                }
            }
        } catch (\Exception $e) {
            throw new \Exception("Failed to list contents: " . $e->getMessage());
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            // Bunny doesn't have a move API, so we need to copy and delete
            $content = $this->read($source);
            $this->write($destination, $content, $config);
            $this->delete($source);
        } catch (\Exception $e) {
            throw new \Exception("Failed to move file: " . $e->getMessage());
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            $content = $this->read($source);
            $this->write($destination, $content, $config);
        } catch (\Exception $e) {
            throw new \Exception("Failed to copy file: " . $e->getMessage());
        }
    }

    /**
     * Check if a file exists at the specified path
     */
    public function fileExists(string $path): bool
    {
        try {
            // Try multiple approaches to check if the file exists
            
            // Approach 1: Try a HEAD request first (most efficient)
            try {
                $response = $this->client->head($this->baseUrl . $path, [
                    'headers' => $this->getHeaders(),
                ]);
                
                if ($response->getStatusCode() === 200) {
                    return true;
                }
            } catch (\Exception $e) {
                // If HEAD fails, continue to the next approach
            }
            
            // Approach 2: Try a GET request with Range header
            try {
                $response = $this->client->get($this->baseUrl . $path, [
                    'headers' => $this->getHeaders([
                        'Range' => 'bytes=0-0' // Only request the first byte
                    ]),
                ]);
                
                if ($response->getStatusCode() === 200 || $response->getStatusCode() === 206) {
                    return true;
                }
            } catch (\Exception $e) {
                // If GET with Range fails, continue to the next approach
            }
            
            // Approach 3: Check if the file appears in the parent directory listing
            $parentPath = dirname($path);
            if ($parentPath === '.') {
                $parentPath = '';
            }
            
            try {
                $contents = $this->listContents($parentPath, false);
                
                if (is_object($contents)) {
                    // Convert iterator to array
                    $items = [];
                    foreach ($contents as $item) {
                        $items[] = $item;
                    }
                    $contents = $items;
                }
                
                foreach ($contents as $item) {
                    if (isset($item['path']) && $item['path'] === $path) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                // If listing fails, we've tried all approaches
            }
            
            return false;
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error("Error in fileExists check for {$path}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a directory exists at the specified path
     */
    public function directoryExists(string $path): bool
    {
        try {
            // Ensure path ends with slash for directories
            $path = rtrim($path, '/') . '/';
            
            $response = $this->client->get($this->baseUrl . $path, [
                'headers' => $this->getHeaders(),
            ]);
            
            // If status code is 200, it's a directory and exists
            if ($response->getStatusCode() === 200) {
                // Try to parse the response as JSON to confirm it's a directory listing
                $contents = json_decode((string) $response->getBody(), true);
                return is_array($contents); // If it's an array, it's a valid directory listing
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
} 