<link rel="stylesheet" href="style.css"></link>
// script.js
document.addEventListener('DOMContentLoaded', ()=>{

  // Generic form validation helper
  function showAlert(msg){ alert(msg); }

  // Booking page logic (if page contains #service select)
  const serviceEl = document.querySelector('#service');
  if (serviceEl){
    const doctorEl = document.querySelector('#doctor');
    const dateEl = document.querySelector('#booking_date');
    const slotsContainer = document.querySelector('#slotsContainer');
    const slotHidden = document.querySelector('#slot_id');

    function filterDoctors(){
      const sid = serviceEl.value;
      Array.from(doctorEl.options).forEach(opt=>{
        if (!opt.value) return;
        if (opt.dataset.serviceId === sid) opt.style.display='block';
        else opt.style.display='none';
      });
      doctorEl.value = '';
      slotsContainer.innerHTML = '';
      slotHidden.value = '';
    }
    serviceEl.addEventListener('change', filterDoctors);

    async function loadSlots(){
      if (!serviceEl.value || !doctorEl.value || !dateEl.value) return;
      const url = `check_slots.php?service_id=${serviceEl.value}&doctor_id=${doctorEl.value}&date=${dateEl.value}`;
      try{
        const res = await fetch(url);
        const data = await res.json();
        renderSlots(data);
      }catch(e){ console.error('slot load error',e) }
    }

    function renderSlots(slots){
      slotsContainer.innerHTML = '';
      slots.forEach(s=>{
        const btn = document.createElement('div');
        btn.className = 'slot' + (s.available ? '' : ' disabled');
        btn.textContent = s.label;
        btn.dataset.id = s.id;
        if (s.available){
          btn.addEventListener('click', ()=>{
            document.querySelectorAll('.slot.selected').forEach(x=>x.classList.remove('selected'));
            btn.classList.add('selected');
            slotHidden.value = s.id;
          });
        }
        slotsContainer.appendChild(btn);
      });
    }

    doctorEl.addEventListener('change', loadSlots);
    dateEl.addEventListener('change', loadSlots);
  }

  // Signup/Login form simple JS checks if present
  const signupForm = document.querySelector('#signupForm');
  if (signupForm){
    signupForm.addEventListener('submit', (ev)=>{
      const pw = signupForm.querySelector('input[name="password"]').value;
      const cpw = signupForm.querySelector('input[name="confirm_password"]').value;
      if (pw.length < 6){ ev.preventDefault(); showAlert('Password must be at least 6 characters'); return; }
      if (pw !== cpw){ ev.preventDefault(); showAlert('Passwords do not match'); return; }
    });
  }

});
