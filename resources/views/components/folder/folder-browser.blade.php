@props(['selectedFolderId' => null, 'inputName' => 'folder_id', 'containerClass' => 'max-h-[300px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded p-2'])

<div {{ $attributes->merge(['class' => $containerClass]) }} id="folder-browser-container">
    <div id="folder-browser" class="w-full">
        <div class="animate-pulse flex justify-center py-4">
            <div class="text-gray-500 dark:text-gray-400">Loading folders...</div>
        </div>
    </div>
    <input type="hidden" name="{{ $inputName }}" id="{{ $inputName }}_input" value="{{ $selectedFolderId }}">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initFolderBrowser();
    });

    function initFolderBrowser() {
        // Load the folder structure
        fetch('{{ route("user.folders.index") }}?ajax=1')
            .then(response => response.json())
            .then(data => {
                renderFolderTree(data, document.getElementById('folder-browser'));
            })
            .catch(error => {
                document.getElementById('folder-browser').innerHTML = 
                    '<div class="text-red-500 p-2">Error loading folders. Please refresh the page.</div>';
                console.error('Error loading folders:', error);
            });
    }
    
    // Render folder tree recursively
    function renderFolderTree(folders, container) {
        container.innerHTML = '';
        
        if (!folders || folders.length === 0) {
            container.innerHTML = '<div class="text-gray-500 dark:text-gray-400 p-2">No folders available</div>';
            return;
        }
        
        const ul = document.createElement('ul');
        ul.className = 'space-y-1';
        
        folders.forEach(folder => {
            const li = document.createElement('li');
            
            const folderDiv = document.createElement('div');
            folderDiv.className = 'flex items-center py-1 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
            folderDiv.dataset.folderId = folder.id;
            
            // Check if this folder is pre-selected
            if (folder.id == '{{ $selectedFolderId }}') {
                folderDiv.classList.add('selected-folder', 'bg-blue-100', 'dark:bg-blue-900/20');
                // Set the hidden input immediately for pre-selected folder
                document.getElementById('{{ $inputName }}_input').value = folder.id;
            }
            
            folderDiv.onclick = function() {
                selectFolder(folder.id, this);
                // Trigger a custom event that parent components can listen for
                const event = new CustomEvent('folderSelected', { 
                    detail: { folderId: folder.id, folderName: folder.name, folderPath: folder.path }
                });
                document.getElementById('folder-browser-container').dispatchEvent(event);
            };
            
            const icon = document.createElement('svg');
            icon.className = 'h-4 w-4 text-yellow-500 mr-2 flex-shrink-0';
            icon.setAttribute('fill', 'currentColor');
            icon.setAttribute('viewBox', '0 0 20 20');
            icon.innerHTML = '<path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H2V6zm0 3h16v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9z" clip-rule="evenodd" />';
            
            const name = document.createElement('span');
            name.className = 'text-sm text-gray-700 dark:text-gray-300 truncate';
            name.textContent = folder.name;
            
            folderDiv.appendChild(icon);
            folderDiv.appendChild(name);
            li.appendChild(folderDiv);
            
            if (folder.children && folder.children.length > 0) {
                const childrenContainer = document.createElement('div');
                childrenContainer.className = 'ml-6 mt-1';
                renderFolderTree(folder.children, childrenContainer);
                li.appendChild(childrenContainer);
            }
            
            ul.appendChild(li);
        });
        
        container.appendChild(ul);
    }
    
    // Handle folder selection
    function selectFolder(folderId, element) {
        // Clear all previously selected folders
        document.querySelectorAll('#folder-browser .selected-folder').forEach(el => {
            el.classList.remove('selected-folder', 'bg-blue-100', 'dark:bg-blue-900/20');
        });
        
        // Mark this folder as selected
        element.classList.add('selected-folder', 'bg-blue-100', 'dark:bg-blue-900/20');
        
        // Update the hidden input
        document.getElementById('{{ $inputName }}_input').value = folderId;
    }
</script>
