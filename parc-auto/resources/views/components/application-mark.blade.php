<div class="flex items-center">
  {{-- <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <path d="M11.395 44.428C4.557 40.198 0 32.632 0 24 0 10.745 10.745 0 24 0a23.891 23.891 0 0113.997 4.502c-.2 17.907-11.097 33.245-26.602 39.926z" fill="#6875F5"/>
    <path d="M14.134 45.885A23.914 23.914 0 0024 48c13.255 0 24-10.745 24-24 0-3.516-.756-6.856-2.115-9.866-4.659 15.143-16.608 27.092-31.75 31.751z" fill="#6875F5"/>
  </svg> --}}

  <svg xmlns="http://www.w3.org/2000/svg"
     width="80"
     height="80"
     viewBox="0 0 24 24"
     fill="currentColor"
      {{ $attributes }}
     >

    <path d="M3 16V8a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1h2.5a2 2 0 0 1 1.6.8L22 13v3h-1a2 2 0 1 1-4 0H9a2 2 0 1 1-4 0H3zm2-8v5h11V8zm12 3v2h3.5l-1.5-2z"/>
</svg>

  <strong class="ml-2 p-2 text-gray-700 dark:text-gray-300">
    {{ _("Parc")}}
  </strong>

  <small class="ml-2 text-gray-500">
    {{_("automobile")}}
  </small>

</div>

