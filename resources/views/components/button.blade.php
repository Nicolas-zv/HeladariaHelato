<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn bg-red-500 hover:bg-red-600 text-white whitespace-nowrap']) }}>
    {{ $slot }}
</button>
