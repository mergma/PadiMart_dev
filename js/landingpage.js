document.addEventListener('DOMContentLoaded', () => {
  // Utility: format price (IDR)
  const formatPrice = v => v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

  // Navbar background on scroll
  const navbar = document.querySelector('.navbar');
  const setNavbarBg = () => {
    if (!navbar) return;
    if (window.scrollY > 50) {
      navbar.style.background = 'rgba(255,255,255,0.98)';
      navbar.style.color = '#0a0a0a';
    } else {
      navbar.style.background = 'transparent';
      navbar.style.color = '';
    }
  };
  setNavbarBg();
  window.addEventListener('scroll', setNavbarBg);

  // Side menu handlers
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sideMenu = document.getElementById('sideMenu');
  const sideMenuOverlay = document.getElementById('sideMenuOverlay');
  const closeMenuBtn = document.getElementById('closeMenuBtn');
  const openMenu = () => { if(sideMenu){ sideMenu.classList.add('active'); sideMenuOverlay?.classList.add('active'); sideMenu.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden'; } };
  const closeMenu = () => { if(sideMenu){ sideMenu.classList.remove('active'); sideMenuOverlay?.classList.remove('active'); sideMenu.setAttribute('aria-hidden','true'); document.body.style.overflow=''; } };
  if(hamburgerBtn) hamburgerBtn.addEventListener('click', openMenu);
  if(closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);
  if(sideMenuOverlay) sideMenuOverlay.addEventListener('click', closeMenu);
  document.querySelectorAll('.side-menu a').forEach(a => a.addEventListener('click', closeMenu));

  // Sample product dataset (prototype)
  const products = [
    { id:1, title:'Beras Organik Premium', category:'Beras', price:120000, img:'https://via.placeholder.com/600x420?text=Beras+Organik', popular:true, created:'2024-09-01', sellerPhone:'+628123456789' },
    { id:2, title:'Pupuk Organik Cair', category:'Pupuk', price:45000, img:'https://via.placeholder.com/600x420?text=Pupuk+Organik', popular:false, created:'2024-08-15', sellerPhone:'+628987654321' },
    { id:3, title:'Benih Padi Unggul', category:'Benih', price:75000, img:'https://via.placeholder.com/600x420?text=Benih+Padi', popular:true, created:'2024-10-10', sellerPhone:'+628112233445' },
    { id:4, title:'Alat Pemipil Padi', category:'Alat', price:250000, img:'https://via.placeholder.com/600x420?text=Alat+Pemipil', popular:false, created:'2024-07-20', sellerPhone:'+628556677889' },
    { id:5, title:'Paket Panduan Budidaya', category:'Edukasi', price:150000, img:'https://via.placeholder.com/600x420?text=Paket+Panduan', popular:true, created:'2024-06-05', sellerPhone:'+628667788990' },
    { id:6, title:'Gandum Olahan', category:'Olahan', price:90000, img:'https://via.placeholder.com/600x420?text=Gandum+Olahan', popular:false, created:'2024-05-01', sellerPhone:'+628223344556' }
  ];

  // Controls
  const productGrid = document.getElementById('productGrid');
  const searchInput = document.getElementById('searchInput');
  const categoryFilter = document.getElementById('categoryFilter');
  const sortSelect = document.getElementById('sortSelect');

  // Render function: Uiverse-style card structure
  function renderProducts(list){
    if(!productGrid) return;
    productGrid.innerHTML = '';
    if(!list || !list.length){ productGrid.innerHTML = '<p>Tidak ada produk sesuai kriteria.</p>'; return; }

    list.forEach((p, idx) => {
      const card = document.createElement('div');
      card.className = 'product-card';
      card.style.animationDelay = `${idx * 60}ms`;

      const badge = p.popular ? `<div class="card__badge">POPULER</div>` : '';

      card.innerHTML = `
        <div class="card__shine"></div>
        <div class="card__glow"></div>
        ${badge}
        <div class="card__content">
          <div class="card__image" style="background-image:url('${p.img}');"></div>
          <div class="card__text">
            <p class="card__title">${p.title}</p>
            <p class="card__description">${p.category}</p>
          </div>
          <div class="card__footer">
            <div class="card__price">Rp ${formatPrice(p.price)}</div>
            <button class="card__button" data-phone="${p.sellerPhone}" aria-label="Chat ${p.title}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a4 4 0 0 1-4 4H7l-4 4V5a2 2 0 0 1 2-2h14a4 4 0 0 1 4 4z"></path></svg>
            </button>
          </div>
        </div>
      `;

      // button handler -> WhatsApp
      const btn = card.querySelector('.card__button');
      if(btn){
        btn.addEventListener('click', (e) => {
          const phone = e.currentTarget.getAttribute('data-phone') || '';
          const cleaned = phone.replace(/\D/g, '');
          window.open(`https://wa.me/${cleaned}`, '_blank', 'noopener');
        });
      }

      productGrid.appendChild(card);
    });
  }

  function applyFilters(){
    const q = (searchInput?.value || '').trim().toLowerCase();
    const cat = categoryFilter?.value || 'all';
    const sort = sortSelect?.value || 'popular';
    let out = products.filter(p => {
      const matchQ = !q || p.title.toLowerCase().includes(q) || p.category.toLowerCase().includes(q);
      const matchCat = (cat === 'all') || (p.category === cat);
      return matchQ && matchCat;
    });
    if(sort === 'popular') out.sort((a,b)=> (b.popular?1:0) - (a.popular?1:0));
    else if(sort === 'new') out.sort((a,b)=> new Date(b.created) - new Date(a.created));
    else if(sort === 'price_asc') out.sort((a,b)=> a.price - b.price);
    else if(sort === 'price_desc') out.sort((a,b)=> b.price - a.price);
    renderProducts(out);
  }

  // events
  searchInput?.addEventListener('input', applyFilters);
  categoryFilter?.addEventListener('change', applyFilters);
  sortSelect?.addEventListener('change', applyFilters);

  // initial
  applyFilters();

  // Testimonial slider (simple)
  const testimonialTrack = document.getElementById('testimonialTrack');
  let tIndex = 0, tInterval = null;
  function showTestimonial(i){
    if(!testimonialTrack) return;
    const items = testimonialTrack.querySelectorAll('.testimonial');
    if(!items.length) return;
    tIndex = (i + items.length) % items.length;
    const w = items[0].offsetWidth + (parseInt(getComputedStyle(items[0]).marginRight) || 16);
    testimonialTrack.style.transform = `translateX(${-(w * tIndex)}px)`;
  }
  function startTestimonials(){ if(tInterval) clearInterval(tInterval); tInterval = setInterval(()=> showTestimonial(tIndex+1), 4000); }
  function stopTestimonials(){ if(tInterval) clearInterval(tInterval); }
  document.querySelectorAll('.testimonial-slider .prev').forEach(b => b.addEventListener('click', ()=>{ showTestimonial(tIndex-1); startTestimonials(); }));
  document.querySelectorAll('.testimonial-slider .next').forEach(b => b.addEventListener('click', ()=>{ showTestimonial(tIndex+1); startTestimonials(); }));
  if(testimonialTrack){ testimonialTrack.addEventListener('mouseenter', stopTestimonials); testimonialTrack.addEventListener('mouseleave', startTestimonials); showTestimonial(0); startTestimonials(); }

  // Accessibility: close menu with ESC
  document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') closeMenu(); });
});
