@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-lg border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:focus:border-primary-400 dark:focus:ring-primary-400 placeholder-gray-400 dark:placeholder-gray-500 disabled:bg-gray-100 dark:disabled:bg-slate-800 disabled:cursor-not-allowed transition-colors duration-200']) }}>
