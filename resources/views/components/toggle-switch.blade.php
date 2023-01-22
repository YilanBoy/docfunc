<div class="relative">
  <input
    type="checkbox"
    {{
      $attributes->merge([
        'type' => 'submit',
        'class' => 'sr-only peer',
      ])
    }}
  />
  <div class="block h-8 w-14 rounded-full bg-gray-400 transition-all duration-300 peer-checked:bg-cyan-400">
  </div>
  <div class="dot absolute left-1 top-1 h-6 w-6 rounded-full bg-white transition-all duration-300 peer-checked:left-7">
  </div>
</div>
