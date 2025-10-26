<template>
  <aside class="bg-slate-900 text-white w-64 p-6 fixed h-full space-y-6">
    <h2 class="text-xl font-semibold">RideZone</h2>
    <nav class="flex flex-col space-y-4">
      <RouterLink to="/" class="hover:text-blue-400">Dashboard</RouterLink>
      <RouterLink to="/cars" class="hover:text-blue-400">Cars</RouterLink>
      <RouterLink to="/dealers" class="hover:text-blue-400">Dealers</RouterLink>
      <RouterLink to="/appointments" class="hover:text-blue-400">Appointments</RouterLink>
    </nav>
  </aside>
</template>
