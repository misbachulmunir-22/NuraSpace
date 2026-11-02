@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-rose-200/80 dark:border-gray-700 dark:bg-gray-900/90 dark:text-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-400/40 rounded-xl shadow-xs transition duration-150']) }}>

