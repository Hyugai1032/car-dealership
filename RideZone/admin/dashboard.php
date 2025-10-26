<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>RideZone Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body.dark {
      background-color: #0f172a;
      color: #e2e8f0;
    }
    body.dark .card {
      background-color: #1e293b;
    }
    body.light {
      background-color: #f8fafc;
      color: #0f172a;
    }
    body.light .card {
      background-color: #e2e8f0;
    }
    .card {
      transition: all 0.3s ease-in-out;
    }
  </style>
</head>

<body class="flex transition-colors duration-500 overflow-x-hidden">
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/sidebar.php';
// require_once __DIR__ . '/../includes/header.php';
?>

<main id="mainContent" class="flex-1 p-8 lg:ml-64 transition-all duration-300 space-y-10">
  <!-- Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-4xl font-extrabold">Admin Dashboard</h1>
      <p class="text-slate-400 text-lg">Welcome back — here's a quick overview.</p>
    </div>
    <button id="toggleTheme" class="px-5 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600">
      Toggle Theme
    </button>
  </div>

  <!-- HERO SHOWCASE (larger car slideshow) -->
  <section id="car-showcase" class="relative rounded-3xl overflow-hidden shadow-2xl h-[400px] lg:h-[480px] flex items-center justify-between px-10">
    <div id="showcase-bg" class="absolute inset-0 transition-all duration-700 z-0"></div>
    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between w-full gap-10">
      <div class="flex-1 text-center lg:text-left">
        <h2 id="car-name" class="text-5xl font-bold text-white mb-4 drop-shadow-md"></h2>
        <p id="car-desc" class="text-lg text-slate-200 max-w-md mx-auto lg:mx-0"></p>
        <div class="mt-8 flex justify-center lg:justify-start gap-4">
          <button id="prevBtn" class="px-5 py-2 rounded bg-white/20 hover:bg-white/30">◀ Prev</button>
          <button id="nextBtn" class="px-5 py-2 rounded bg-white/20 hover:bg-white/30">Next ▶</button>
          <button id="pauseBtn" class="px-5 py-2 rounded bg-white/10 hover:bg-white/20">Pause</button>
        </div>
      </div>
      <div class="flex-1 flex justify-center">
        <img id="car-image" src="" alt="Car" class="w-full max-w-md object-contain drop-shadow-2xl transition-opacity duration-700 rounded-2xl" />
      </div>
    </div>
  </section>

  <!-- DASHBOARD STATS GRID -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="card p-6 rounded-xl shadow text-center">
      <div class="text-slate-400 text-sm">Total Cars</div>
      <div id="totalCars" class="text-4xl font-bold text-blue-500 mt-2">--</div>
    </div>
    <div class="card p-6 rounded-xl shadow text-center">
      <div class="text-slate-400 text-sm">Dealers</div>
      <div id="totalDealers" class="text-4xl font-bold text-emerald-500 mt-2">--</div>
    </div>
    <div class="card p-6 rounded-xl shadow text-center">
      <div class="text-slate-400 text-sm">Pending Appointments</div>
      <div id="pendingAppointments" class="text-4xl font-bold text-yellow-500 mt-2">--</div>
    </div>
    <div class="card p-6 rounded-xl shadow text-center">
      <div class="text-slate-400 text-sm">Users</div>
      <div id="totalUsers" class="text-4xl font-bold text-pink-500 mt-2">--</div>
    </div>
  </div>

  <!-- ACTIVITY + CHARTS -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="card p-6 rounded-xl shadow lg:col-span-1">
      <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
      <ul id="recentActivities" class="text-slate-400 text-sm space-y-2">
        <li>Loading...</li>
      </ul>
    </div>

    <div class="card p-6 rounded-xl shadow lg:col-span-2 grid grid-cols-2 gap-6">
      <div>
        <h3 class="font-semibold mb-3">Car Types Distribution</h3>
        <canvas id="typesChart" height="200"></canvas>
      </div>
      <div>
        <h3 class="font-semibold mb-3">Monthly Sales</h3>
        <canvas id="salesChart" height="200"></canvas>
      </div>
    </div>
  </div>

  <!-- QUICK ACTIONS -->
  <div class="card p-5 rounded-xl shadow mt-8 text-center">
    <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
    <div class="flex justify-center gap-4 flex-wrap">
      <a href="cars.php" class="px-4 py-2 bg-blue-600 rounded-lg text-white hover:bg-blue-700">Cars</a>
      <a href="#" onclick="alert('Dealers page coming soon!')" class="px-4 py-2 bg-emerald-600 rounded-lg text-white hover:bg-emerald-700">Dealers</a>
      <a href="#" onclick="alert('Appointments page coming soon!')" class="px-4 py-2 bg-yellow-500 rounded-lg text-white hover:bg-yellow-600">Appointments</a>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<script>
/* -------------------- THEME TOGGLE -------------------- */
const body = document.body;
const toggleBtn = document.getElementById("toggleTheme");
function setTheme(theme) {
  if (theme === "dark") { body.classList.add("dark"); localStorage.setItem("theme","dark"); }
  else { body.classList.remove("dark"); localStorage.setItem("theme","light"); }
}
toggleBtn.addEventListener("click", () => {
  const current = localStorage.getItem("theme") === "dark" ? "light" : "dark";
  setTheme(current);
});
document.addEventListener("DOMContentLoaded", () => {
  setTheme(localStorage.getItem("theme") || "dark");
});

/* -------------------- CAR SHOWCASE (GSAP) -------------------- */
const cars = [
  { name:"Tesla Model S", desc:"Electric performance with luxury.", img:"../assets/image/tesla.png", bg:"linear-gradient(90deg,#6366f1,#a855f7)" },
  { name:"Lamborghini Huracán", desc:"Supercar speed and Italian design.", img:"../assets/image/lamborghini_huracan.png", bg:"linear-gradient(90deg,#f59e0b,#ef4444)" },
  { name:"BMW M4", desc:"Precision engineering and raw power.", img:"../assets/image/bmw_m4.png", bg:"linear-gradient(90deg,#3b82f6,#06b6d4)" }
];
let current=0, auto;
const img=document.getElementById("car-image"), nameTxt=document.getElementById("car-name"), descTxt=document.getElementById("car-desc"), bg=document.getElementById("showcase-bg");

function showCar(i){
  current=(i+cars.length)%cars.length;
  const car=cars[current];
  gsap.to(img,{opacity:0,duration:0.5,onComplete:()=>{
    img.src=car.img;
    nameTxt.textContent=car.name;
    descTxt.textContent=car.desc;
    gsap.to(img,{opacity:1,duration:0.5});
  }});
  gsap.to(bg,{background:car.bg,duration:1});
}
document.getElementById("nextBtn").onclick=()=>showCar(current+1);
document.getElementById("prevBtn").onclick=()=>showCar(current-1);
document.getElementById("pauseBtn").onclick=()=>clearInterval(auto);
window.onload=()=>{showCar(0);auto=setInterval(()=>showCar(current+1),4000);};

/* -------------------- STATS -------------------- */
function random(min,max){return Math.floor(Math.random()*(max-min+1))+min;}
function loadStats(){
  document.getElementById("totalCars").textContent=random(40,100);
  document.getElementById("totalDealers").textContent=random(5,15);
  document.getElementById("pendingAppointments").textContent=random(3,12);
  document.getElementById("totalUsers").textContent=random(200,500);

  const activities=[
    "User John purchased a Tesla.",
    "New dealer 'MotorMax' registered.",
    "Appointment scheduled for 3PM.",
    "BMW M4 restocked.",
    "Lamborghini Huracán reserved."
  ];
  const list=document.getElementById("recentActivities");
  list.innerHTML="";
  activities.slice(0,3).forEach(act=>{
    const li=document.createElement("li");
    li.textContent=act;
    list.appendChild(li);
  });
}
loadStats();

/* -------------------- CHARTS -------------------- */
const ctx1=document.getElementById("typesChart").getContext("2d");
new Chart(ctx1,{
  type:"doughnut",
  data:{
    labels:["SUV","Sedan","Truck","Sports"],
    datasets:[{data:[35,45,10,25],
      backgroundColor:["#3b82f6","#10b981","#f59e0b","#ef4444"],
      borderWidth:1}]
  },
  options:{plugins:{legend:{display:true,position:'bottom'}}}
});

const ctx2=document.getElementById("salesChart").getContext("2d");
new Chart(ctx2,{
  type:"bar",
  data:{
    labels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct"],
    datasets:[{label:"Sales",data:Array.from({length:10},()=>random(20,100)),
      backgroundColor:"#6366f1"}]
  },
  options:{
    plugins:{legend:{display:false}},
    scales:{y:{beginAtZero:true}}
  }
});


// window.addEventListener("resize", () => {
//   const main = document.getElementById("mainContent");
//   if (window.innerWidth >= 1024) {
//     main.classList.add("lg:ml-64");
//   } else {
//     main.classList.remove("lg:ml-64");
//   }
// });

</script>
</body>
</html>
