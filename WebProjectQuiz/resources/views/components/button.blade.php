<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark: border border-transparent rounded-md font-semibold text-xs dark:text-white-400 uppercase tracking-widest hover:bg-red-700 hover:text-gray dark:hover: focus:bg-red-700 dark:focus: active:bg-red-900 dark:active:bg-red-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-red-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
