// assets/js/app.js
(async function(){
  const base = ''; // if BASE_URL not root, set path like '/RideZone'
  const api = base + '/api/cars_api.php';
  const carsTbody = document.getElementById('carsTbody');
  const searchInput = document.getElementById('searchInput');

  // modal & form elements
  const openAddBtn = document.getElementById('openAddBtn');
  const carModal = document.getElementById('carModal');
  const closeModal = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelBtn');
  const modalTitle = document.getElementById('modalTitle');
  const carForm = document.getElementById('carForm');
  const previewImage = document.getElementById('previewImage');

  // form fields shortcuts
  const carId = document.getElementById('carId');
  const make = document.getElementById('make');
  const model = document.getElementById('model');
  const type = document.getElementById('type');
  const year = document.getElementById('year');
  const price = document.getElementById('price');
  const dealer = document.getElementById('dealer');
  const description = document.getElementById('description');
  const warranty = document.getElementById('warranty');
  const image = document.getElementById('image');

  function showModal() { carModal.classList.remove('hidden'); }
  function hideModal() { carModal.classList.add('hidden'); resetForm(); }

  openAddBtn?.addEventListener('click', async ()=>{
    modalTitle.textContent = 'Add Car';
    showModal();
    carId.value = '';
    await loadDealers();
  });

  closeModal?.addEventListener('click', hideModal);
  cancelBtn?.addEventListener('click', hideModal);

  // preview image
  image?.addEventListener('change', (e)=>{
    const f = e.target.files[0];
    if (!f) return previewImage.classList.add('hidden');
    const url = URL.createObjectURL(f);
    previewImage.src = url;
    previewImage.classList.remove('hidden');
  });

  // load list of dealers into select
  async function loadDealers() {
    const res = await fetch(api + '?action=getDealers');
    const arr = await res.json();
    dealer.innerHTML = '<option value="">-- None --</option>' + arr.map(d=>`<option value="${d.id}">${escapeHtml(d.name)}</option>`).join('');
  }

  // fetch and render cars
  async function fetchCars(q='') {
    const res = await fetch(api + '?action=fetch&q=' + encodeURIComponent(q));
    const arr = await res.json();
    renderCars(arr);
  }

  function renderCars(arr) {
    const html = arr.map(c => {
      const imgUrl = c.image ? ('<?= UPLOADS_URL ?>' + c.image) : 'https://via.placeholder.com/200x120?text=No+Image';
      return `
      <tr class="hover:bg-slate-600">
        <td class="px-4 py-3">${c.id}</td>
        <td class="px-4 py-3"><img src="${imgUrl}" class="w-28 h-18 object-cover rounded" /></td>
        <td class="px-4 py-3">${escapeHtml(c.make)}</td>
        <td class="px-4 py-3">${escapeHtml(c.model)}</td>
        <td class="px-4 py-3">${escapeHtml(c.type)}</td>
        <td class="px-4 py-3">₱ ${Number(c.price).toLocaleString()}</td>
        <td class="px-4 py-3">${escapeHtml(c.dealer_name ?? '—')}</td>
        <td class="px-4 py-3 text-center">
          <button class="editBtn px-3 py-1 bg-emerald-500 rounded" data-id="${c.id}">Edit</button>
          <button class="delBtn px-3 py-1 bg-rose-500 rounded ml-2" data-id="${c.id}">Delete</button>
        </td>
      </tr>`;
    }).join('');
    carsTbody.innerHTML = html;

    // attach handlers
    document.querySelectorAll('.editBtn').forEach(btn => btn.addEventListener('click', onEdit));
    document.querySelectorAll('.delBtn').forEach(btn => btn.addEventListener('click', onDelete));
  }

  async function onEdit(e) {
    const id = e.currentTarget.dataset.id;
    // get single car (we reuse fetch list and pick)
    const res = await fetch(api + '?action=fetch&q=' + encodeURIComponent(''));
    const arr = await res.json();
    const car = arr.find(c=>c.id == id);
    if (!car) return alert('Car not found');
    modalTitle.textContent = 'Edit Car';
    carId.value = car.id;
    make.value = car.make;
    model.value = car.model;
    type.value = car.type;
    year.value = car.year;
    price.value = car.price;
    description.value = car.description || '';
    warranty.value = car.warranty_info || '';
    await loadDealers();
    if (car.dealer_id) dealer.value = car.dealer_id;
    // preview image
    if (car.image) {
      previewImage.src = '<?= UPLOADS_URL ?>' + car.image;
      previewImage.classList.remove('hidden');
    } else {
      previewImage.classList.add('hidden');
      previewImage.src = '#';
    }
    showModal();
  }

  async function onDelete(e) {
    const id = e.currentTarget.dataset.id;
    if (!confirm('Delete this car?')) return;
    const res = await fetch(api + '?action=delete&id=' + encodeURIComponent(id));
    const json = await res.json();
    if (json.success) {
      toast(json.success);
      fetchCars(searchInput.value);
    } else {
      toast('Delete failed', true);
    }
  }

  // submit form (add/edit)
  carForm?.addEventListener('submit', async (ev)=>{
    ev.preventDefault();
    const fd = new FormData(carForm);
    // include file input - already in form
    const res = await fetch(api + '?action=save', { method: 'POST', body: fd });
    const json = await res.json();
    if (json.success) {
      toast(json.success);
      hideModal();
      fetchCars(searchInput.value);
    } else if (json.error) {
      toast(json.error, true);
    } else {
      toast('Unexpected response', true);
    }
  });

  // simple toast
  function toast(msg, err=false) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.className = (err? 'bg-rose-600' : 'bg-emerald-500') + ' text-white px-4 py-2 rounded fixed right-6 bottom-6 z-60 shadow';
    document.body.appendChild(t);
    setTimeout(()=> t.remove(), 3000);
  }

  // search handler
  searchInput?.addEventListener('keyup', (e)=>{
    fetchCars(e.target.value);
  });

  // reset form
  function resetForm(){
    carForm.reset();
    previewImage.src = '#';
    previewImage.classList.add('hidden');
  }

  // helper
  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]); });
  }

  // initial load
  await fetchCars();
})();
