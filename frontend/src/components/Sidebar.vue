<template>
  <aside class="bg-slate-900 text-white w-64 p-6 fixed h-full space-y-6">
    <h2 class="text-xl font-semibold">RideZone</h2>
    <nav class="flex flex-col space-y-4">
      <RouterLink to="/" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">📊 Dashboard</RouterLink>
      <RouterLink to="/cars" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">🚘 Cars</RouterLink>
      <RouterLink to="/dealers" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">🤝 Dealers</RouterLink>
      <RouterLink to="/appointments" class="block py-2 px-4 rounded hover:bg-indigo-600 hover:text-white">📅 Appointments</RouterLink>
    </nav>
  </aside>
  <button id="openSidebar" class="fixed lg:hidden top-4 left-4 bg-indigo-600 text-white px-3 py-2 rounded shadow-md z-50">
    ☰
  </button> 
</template>

