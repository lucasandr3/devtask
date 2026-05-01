<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-lg font-semibold text-sm text-gray-700 dark:text-gray-200 tracking-wide shadow-sm hover:bg-gray-50 dark:hover:bg-slate-600 hover:border-gray-400 dark:hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 disabled:opacity-50 transition-all duration-200']) }}>
    {{ $slot }}
</button>
