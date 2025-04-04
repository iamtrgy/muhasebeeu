@props(['folder'])

@if($folder->canUpload(auth()->user()))
    <form action="{{ route('user.files.upload', $folder) }}" method="POST" enctype="multipart/form-data" class="flex items-center" id="uploadForm">
        @csrf
        <input type="file" name="files[]" id="files" multiple class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt">
        
        <!-- AI Auto-Classify Toggle -->
        <div x-data="{ aiEnabled: false }" class="flex items-center mr-4">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="ai_classify" class="sr-only peer" x-model="aiEnabled">
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">AI Auto-Classify</span>
            </label>
            <span class="ml-2 text-xs text-gray-500" x-show="aiEnabled">(AI will suggest the best folder)</span>
        </div>

        <button type="button" onclick="document.getElementById('files').click()" class="inline-flex items-center h-10 px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            <span id="uploadButtonText">{{ __('Upload Files') }}</span>
        </button>
        <div class="text-xs text-gray-500 ml-2">Max 10MB per file (jpg, png, pdf, doc, xls, txt)</div>
        <div id="uploadProgress" class="hidden ml-4 w-64">
            <div class="bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <div class="text-xs text-gray-500 mt-1" id="progressText">0%</div>
        </div>
    </form>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('files');
            const uploadForm = document.getElementById('uploadForm');
            const uploadButtonText = document.getElementById('uploadButtonText');
            const uploadProgress = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            
            // Allowed file types (must match the accept attribute and server validation)
            const allowedTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 
                'application/pdf', 
                'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain'
            ];
            
            fileInput.addEventListener('change', async function() {
            if (this.files.length > 0) {
                    console.log('File selected, checking size and preparing to upload...');
                    
                    // Check if any files are larger than the limit or have invalid types
                    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB in bytes
                    let invalidFiles = [];
                    let oversizedFiles = [];
                    let totalSize = 0;
                    
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        totalSize += file.size;
                        console.log(`File: ${file.name}, Size: ${(file.size / (1024 * 1024)).toFixed(2)} MB, Type: ${file.type}`);
                        
                        // Check file size
                        if (file.size > MAX_FILE_SIZE) {
                            oversizedFiles.push({
                                name: file.name,
                                size: (file.size / (1024 * 1024)).toFixed(2) + ' MB'
                            });
                        }
                        
                        // Check file type
                        if (!allowedTypes.includes(file.type)) {
                            invalidFiles.push({
                                name: file.name,
                                type: file.type || 'unknown'
                            });
                        }
                    }
                    
                    console.log(`Total upload size: ${(totalSize / (1024 * 1024)).toFixed(2)} MB`);
                    
                    // Handle oversized files
                    if (oversizedFiles.length > 0) {
                        let errorMessage = 'The following files exceed the 10MB size limit:\n';
                        oversizedFiles.forEach(file => {
                            errorMessage += `- ${file.name} (${file.size})\n`;
                        });
                        errorMessage += '\nPlease select smaller files.';
                        alert(errorMessage);
                        // Reset the file input
                        this.value = '';
                        return;
                    }
                    
                    // Handle invalid file types
                    if (invalidFiles.length > 0) {
                        let errorMessage = 'The following files have invalid types:\n';
                        invalidFiles.forEach(file => {
                            errorMessage += `- ${file.name} (${file.type})\n`;
                        });
                        errorMessage += '\nAllowed types: jpg, png, pdf, doc, xls, txt';
                        alert(errorMessage);
                        // Reset the file input
                        this.value = '';
                        return;
                    }
                    
                    try {
                        // For files larger than 2MB, we need to use chunked uploads
                        const MAX_CHUNK_SIZE = 1 * 1024 * 1024; // 1MB chunks
                        
                        if (this.files.length === 1 && this.files[0].size > 2 * 1024 * 1024) {
                            const file = this.files[0];
                            
                            // Show progress bar
                            uploadProgress.classList.remove('hidden');
                            uploadButtonText.textContent = 'Uploading...';
                            progressBar.style.width = '0%';
                            progressText.textContent = 'Preparing chunked upload...';
                            
                            // Get CSRF token directly from the form
                            const csrfToken = document.querySelector('input[name="_token"]').value;
                            console.log('Using CSRF token for chunked upload:', csrfToken);
                            
                            // Create a temporary filename for reassembly
                            const tempFilename = 'temp_' + Date.now() + '_' + file.name;
                            console.log('Temporary filename for chunked upload:', tempFilename);
                            
                            // Function to upload a chunk
                            const uploadChunk = async (start, end, chunkIndex, totalChunks) => {
                                const chunk = file.slice(start, end);
                                const chunkForm = new FormData();
                                
                                // Add the temp filename, chunk index, and total chunks
                                chunkForm.append('_token', csrfToken);
                                chunkForm.append('chunk', chunk);
                                chunkForm.append('chunk_index', chunkIndex);
                                chunkForm.append('total_chunks', totalChunks);
                                chunkForm.append('original_name', file.name);
                                chunkForm.append('temp_filename', tempFilename);
                                chunkForm.append('filename', file.name);
                                chunkForm.append('file_size', file.size);
                                chunkForm.append('mime_type', file.type);
                                // Add AI classify parameter
                                chunkForm.append('ai_classify', document.querySelector('input[name="ai_classify"]').checked ? '1' : '0');
                                
                                // If this is the last chunk, add a flag
                                if (chunkIndex === totalChunks - 1) {
                                    chunkForm.append('is_last_chunk', 'true');
                                }
                                
                                return new Promise((resolve, reject) => {
                                    const xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('user.files.chunk', $folder) }}', true);
                                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    
                                    xhr.onload = function() {
                                        console.log(`Chunk ${chunkIndex + 1}/${totalChunks} response:`, xhr.status, xhr.responseText);
                                        
                                        if (xhr.status >= 200 && xhr.status < 300) {
                                            let response;
                                            try {
                                                response = JSON.parse(xhr.responseText);
                                                console.log(`Chunk ${chunkIndex + 1}/${totalChunks} parsed response:`, response);
                                                resolve(response);
                                            } catch (e) {
                                                console.error(`Chunk ${chunkIndex + 1}/${totalChunks} JSON parse error:`, e);
                                                reject(new Error('Invalid JSON response from server'));
                                            }
                                        } else {
                                            console.error(`Chunk ${chunkIndex + 1}/${totalChunks} HTTP error:`, xhr.status, xhr.statusText, xhr.responseText);
                                            reject(new Error(`HTTP error ${xhr.status}: ${xhr.statusText}`));
                                        }
                                    };
                                    
                                    xhr.onerror = function() {
                                        console.error(`Chunk ${chunkIndex + 1}/${totalChunks} network error`);
                                        reject(new Error('Network error occurred'));
                                    };
                                    
                                    xhr.upload.onprogress = function(e) {
                                        if (e.lengthComputable) {
                                            // Calculate overall progress
                                            const chunkProgress = (e.loaded / e.total) * 100;
                                            const overallProgress = ((chunkIndex / totalChunks) * 100) + (chunkProgress / totalChunks);
                                            progressBar.style.width = `${Math.round(overallProgress)}%`;
                                            progressText.textContent = `${Math.round(overallProgress)}% (Chunk ${chunkIndex + 1}/${totalChunks})`;
                                        }
                                    };
                                    
                                    xhr.send(chunkForm);
                                });
                            };
                            
                            // Calculate the number of chunks
                            const totalChunks = Math.ceil(file.size / MAX_CHUNK_SIZE);
                            console.log(`File will be uploaded in ${totalChunks} chunks`);
                            
                            // Upload each chunk sequentially
                            try {
                                for (let i = 0; i < totalChunks; i++) {
                                    const start = i * MAX_CHUNK_SIZE;
                                    const end = Math.min(file.size, start + MAX_CHUNK_SIZE);
                                    
                                    // Wait for this chunk to complete before moving to the next
                                    const response = await uploadChunk(start, end, i, totalChunks);
                                    console.log(`Chunk ${i + 1}/${totalChunks} completed:`, response);
                                    
                                    // If this is the last chunk and it completed successfully
                                    if (i === totalChunks - 1 && response.success) {
                                        progressText.textContent = 'Upload complete! Refreshing...';
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 500);
                                        return;
                                    }
                                }
                            } catch (error) {
                                console.error('Error in chunked upload:', error);
                                progressBar.style.backgroundColor = '#ef4444'; // Red
                                progressText.textContent = 'Upload failed';
                                uploadButtonText.textContent = 'Upload Failed';
                                alert('Failed to upload file: ' + error.message);
                                
                                // Reset after 3 seconds
                                setTimeout(() => {
                                    uploadButtonText.textContent = 'Upload Files';
                                    uploadProgress.classList.add('hidden');
                                    // Reset file input
                                    fileInput.value = '';
                                }, 3000);
                            }
                        } else {
                            // For smaller files or multiple files, use the standard approach
                            // Use FormData and XMLHttpRequest for better control over the upload
                            const formData = new FormData(uploadForm);
                            // Ensure AI classify parameter is included
                            formData.append('ai_classify', document.querySelector('input[name="ai_classify"]').checked ? '1' : '0');
                            const xhr = new XMLHttpRequest();

                            // Show progress bar
                            uploadProgress.classList.remove('hidden');
                            uploadButtonText.textContent = 'Uploading...';
                            
                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                                    progressBar.style.width = percentComplete + '%';
                                    progressText.textContent = percentComplete + '%';
                                    console.log(`Upload progress: ${percentComplete}%`);
                                }
                            });
                            
                            xhr.addEventListener('load', function() {
                                console.log('Upload completed with status:', xhr.status);
                                console.log('Response:', xhr.responseText);
                                
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    console.log('Parsed response:', response);
                                    
                                    if (xhr.status >= 200 && xhr.status < 300 && response.success) {
                                        progressBar.style.backgroundColor = '#22c55e'; // Green
                                        progressText.textContent = 'Upload complete! Refreshing...';
                                        
                                        // Redirect or refresh after a short delay
                                        setTimeout(() => {
                                            window.location.reload(); // Reload the page
                                        }, 500); 
                                    } else {
                                        // Handle server-side error (e.g., validation failed)
                                        progressBar.style.backgroundColor = '#ef4444'; // Red
                                        progressText.textContent = 'Upload failed';
                                        uploadButtonText.textContent = 'Upload Failed';
                                        alert(response.message || 'An error occurred during upload.');
                                        
                                        // Reset after 3 seconds
                                        setTimeout(() => {
                                            uploadButtonText.textContent = 'Upload Files';
                                            uploadProgress.classList.add('hidden');
                                            // Reset file input
                                            fileInput.value = '';
                                        }, 3000);
                                    }
                                } catch (e) {
                                    console.error("JSON parse error on standard upload:", e, xhr.responseText);
                                    progressBar.style.backgroundColor = '#ef4444'; // Red
                                    progressText.textContent = 'Error processing response';
                                    uploadButtonText.textContent = 'Error';
                                    alert('An unexpected error occurred after upload.');

                                    // Reset after 3 seconds
                                    setTimeout(() => {
                                        uploadButtonText.textContent = 'Upload Files';
                                        uploadProgress.classList.add('hidden');
                                        // Reset file input
                                        fileInput.value = '';
                                    }, 3000);
                                }
                            });
                            
                            xhr.addEventListener('error', function() {
                                console.error('Network error during standard upload.');
                                progressBar.style.backgroundColor = '#ef4444'; // Red
                                progressText.textContent = 'Network Error';
                                uploadButtonText.textContent = 'Upload Failed';
                                alert('A network error occurred. Please check your connection and try again.');

                                // Reset after 3 seconds
                                setTimeout(() => {
                                    uploadButtonText.textContent = 'Upload Files';
                                    uploadProgress.classList.add('hidden');
                                    // Reset file input
                                    fileInput.value = '';
                                }, 3000);
                            });

                            // Send the request
                            xhr.open('POST', uploadForm.action, true); // Use form action directly
                            xhr.setRequestHeader('Accept', 'application/json'); // Expect JSON response
                            xhr.send(formData);
                        }
                    } catch(e) {
                        console.error('Error starting upload:', e);
                        alert('Error uploading file: ' + e.message);
                    }
                }
            });
        });
    </script>
@endif 