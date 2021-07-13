<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white
            uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900
            focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150'
    ]) }}
>
        {{ $slot }}
</button>
