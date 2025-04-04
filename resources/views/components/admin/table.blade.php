@props([
    'id' => null,
    'containerClass' => '',
    'tableClass' => '',
    'darkMode' => true,
])

<div class="overflow-x-auto {{ $containerClass }}">
    <table {{ $id ? "id=\"{$id}\"" : '' }} class="min-w-full divide-y divide-gray-200 {{ $darkMode ? 'dark:divide-gray-700' : '' }} {{ $tableClass }}">
        <thead class="bg-gray-50 {{ $darkMode ? 'dark:bg-gray-700' : '' }}">
            {{ $header }}
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 {{ $darkMode ? 'dark:bg-gray-800 dark:divide-gray-700' : '' }}">
            {{ $slot }}
        </tbody>
        @if(isset($footer))
            <tfoot>
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>
