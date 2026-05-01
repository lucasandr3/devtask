<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 dark:bg-red-500 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-red-700 dark:hover:bg-red-600 active:bg-red-800 dark:active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200 shadow-sm hover:shadow-md']) }}>
    {{ $slot }}
</button>
