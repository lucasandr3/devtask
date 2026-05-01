<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 dark:bg-primary-500 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-primary-700 dark:hover:bg-primary-600 focus:bg-primary-700 dark:focus:bg-primary-600 active:bg-primary-800 dark:active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200 shadow-sm hover:shadow-md']) }}>
    {{ $slot }}
</button>
