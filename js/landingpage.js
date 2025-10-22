document.addEventListener('DOMContentLoaded', () => {
  // Smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      // allow external links with target
      if (this.getAttribute('target') === '_blank') return;
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Navbar background on scroll
  const navbar = document.querySelector('.navbar');
  const setNavbarBg = () => {
    if (window.scrollY > 50) {
      navbar.style.background = 'rgba(255,255,255,0.98)';
      navbar.style.color = '#0a0a0a';
    } else {
      navbar.style.background = 'rgba(10, 10, 10, 0.12)';
      navbar.style.color = '';
    }
  };
  setNavbarBg();
  window.addEventListener('scroll', setNavbarBg);

  // Hamburger / side menu
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sideMenu = document.getElementById('sideMenu');
  const sideMenuOverlay = document.getElementById('sideMenuOverlay');
  const closeMenuBtn = document.getElementById('closeMenuBtn');

  const openMenu = () => {
    sideMenu.classList.add('active');
    sideMenuOverlay.classList.add('active');
    sideMenu.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  };
  const closeMenu = () => {
    sideMenu.classList.remove('active');
    sideMenuOverlay.classList.remove('active');
    sideMenu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  hamburgerBtn.addEventListener('click', openMenu);
  closeMenuBtn.addEventListener('click', closeMenu);
  sideMenuOverlay.addEventListener('click', closeMenu);
  document.querySelectorAll('.side-menu a').forEach(a => a.addEventListener('click', closeMenu));

  // Sample product data (for prototype)
  const products = [
    { id:1, title:'Beras Pandan Wangi 5kg', category:'beras', price:78000, img:'https://via.placeholder.com/400x300?text=Beras+Pandan', popular:95, created:'2025-06-01', sellerPhone:'+628123456789' },
    { id:2, title:'Keripik Pisang Manis', category:'camilan', price:20000, img:'https://via.placeholder.com/400x300?text=Keripik+Pisang', popular:80, created:'2025-09-10', sellerPhone:'+628123456789' },
    { id:3, title:'Anyaman Khas Tabalong', category:'kerajinan', price:120000, img:'https://via.placeholder.com/400x300?text=Anyaman', popular:60, created:'2025-03-15', sellerPhone:'+628123456789' },
    { id:4, title:'Beras Organik 2kg', category:'beras', price:45000, img:'https://via.placeholder.com/400x300?text=Beras+Organik', popular:70, created:'2025-08-01', sellerPhone:'+628123456789' },
    { id:5, title:'Camilan Kacang Bumbu', category:'camilan', price:25000, img:'https://via.placeholder.com/400x300?text=Kacang+Bumbu', popular:50, created:'2025-07-22', sellerPhone:'+628123456789' },
    { id:6, title:'Souvenir Keramik', category:'kerajinan', price:65000, img:'https://via.placeholder.com/400x300?text=Keramik', popular:40, created:'2025-05-05', sellerPhone:'+628123456789' }
  ];

  // Elements
  const productGrid = document.getElementById('productGrid');
  const searchInput = document.getElementById('searchInput');
  const categoryFilter = document.getElementById('categoryFilter');
  const sortSelect = document.getElementById('sortSelect');

  function formatPrice(v){ return 'Rp ' + v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

  function renderProducts(list){
    productGrid.innerHTML = '';
    if(!list.length){ productGrid.innerHTML = '<p>Tidak ada produk sesuai kriteria.</p>'; return; }
    list.forEach((p, idx) => {
      const card = document.createElement('div'); card.className = 'product-card';
      // staggered entrance
      card.style.animationDelay = (idx * 80) + 'ms';
      card.innerHTML = `
        <img class="product-thumb" src="${p.img}" alt="${p.title}">
        <div class="product-body">
          <div class="product-title">${p.title}</div>
          <div class="product-meta">Kategori: ${p.category}</div>
        </div>
        <div class="product-footer">
          <div class="price">${formatPrice(p.price)}</div>
          <a class="whatsapp-cta" href="https://wa.me/${p.sellerPhone.replace(/[^0-9]/g,'')}?text=${encodeURIComponent('Halo, saya tertarik dengan produk: '+p.title)}" target="_blank" rel="noopener">Chat</a>
        </div>
        <div class="product-overlay">
          <div class="overlay-meta">
            <div class="overlay-price">${formatPrice(p.price)}</div>
            <a class="overlay-cta" href="https://wa.me/${p.sellerPhone.replace(/[^0-9]/g,'')}?text=${encodeURIComponent('Halo, saya tertarik dengan produk: '+p.title)}" target="_blank" rel="noopener">Chat</a>
          </div>
        </div>
      `;
      productGrid.appendChild(card);
    });
  }

  // --- Testimonials slider ---
  const testimonialTrack = document.getElementById('testimonialTrack');
  const testimonials = testimonialTrack ? testimonialTrack.children : [];
  let tIndex = 0;
  let tInterval = null;
  function showTestimonial(i){
    if(!testimonialTrack) return;
    tIndex = (i + testimonials.length) % testimonials.length;
    testimonialTrack.style.transform = `translateX(-${tIndex * 100}%)`;
  }
  function startTestimonials(){
    if(tInterval) clearInterval(tInterval);
    tInterval = setInterval(()=> showTestimonial(tIndex+1), 4000);
  }
  function stopTestimonials(){ if(tInterval) clearInterval(tInterval); }
  // controls
  const prevBtn = document.querySelector('.testimonial-slider .prev');
  const nextBtn = document.querySelector('.testimonial-slider .next');
  if(prevBtn) prevBtn.addEventListener('click', ()=>{ showTestimonial(tIndex-1); startTestimonials(); });
  if(nextBtn) nextBtn.addEventListener('click', ()=>{ showTestimonial(tIndex+1); startTestimonials(); });
  if(testimonialTrack){
    testimonialTrack.addEventListener('mouseenter', stopTestimonials);
    testimonialTrack.addEventListener('mouseleave', startTestimonials);
    // initialize
    showTestimonial(0);
    startTestimonials();
  }

  function applyFilters(){
    const q = (searchInput.value || '').trim().toLowerCase();
    const cat = categoryFilter.value;
    const sort = sortSelect.value;
    let out = products.filter(p => {
      const matchQ = !q || p.title.toLowerCase().includes(q) || p.category.toLowerCase().includes(q);
      const matchCat = (cat === 'all') || (p.category === cat);
      return matchQ && matchCat;
    });
    // sort
    if(sort === 'popular') out.sort((a,b)=> b.popular - a.popular);
    else if(sort === 'new') out.sort((a,b)=> new Date(b.created) - new Date(a.created));
    else if(sort === 'price_asc') out.sort((a,b)=> a.price - b.price);
    else if(sort === 'price_desc') out.sort((a,b)=> b.price - a.price);
    renderProducts(out);
  }

  // events
  searchInput.addEventListener('input', ()=> applyFilters());
  categoryFilter.addEventListener('change', ()=> applyFilters());
  sortSelect.addEventListener('change', ()=> applyFilters());

  // initial render
  applyFilters();

  // Accessibility: close menu with ESC
  document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') closeMenu(); });

});
