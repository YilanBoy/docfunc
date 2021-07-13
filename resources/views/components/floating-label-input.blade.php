<div class="relative">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value ?? '' }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes }}
        class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
        placeholder-transparent focus:outline-none focus:border-blue-500 transition duration-150 ease-in
        dark:bg-gray-600 dark:text-white"
    >

    <label
        for="{{ $name }}"
        class="absolute left-0 -top-3.5 text-gray-600 text-sm
        transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
        peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm
        dark:text-white dark:peer-placeholder-shown:text-white dark:peer-focus:text-white"
    >
        {{ $placeholder }}
    </label>
</div>
