document.addEventListener('DOMContentLoaded', () => {
  // Utility: format price (IDR)
  const formatPrice = v => v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

  // Navbar background on scroll
  const navbar = document.querySelector('.navbar');
  const setNavbarBg = () => {
    if (!navbar) return;
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  };
    // Navbar auto-hide on scroll (hide when scrolling down, show when scrolling up)
  // initialize lastScroll reliably
  // initialize lastScroll reliably
  let lastScroll = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop || 0;
  let ticking = false;
  let lastHideTs = 0;
    const handleNavbarAutoHide = () => {
      if (!navbar) return;
      const current = window.scrollY || 0;
      // threshold to avoid jitter
      const delta = current - lastScroll;
      if (Math.abs(delta) < 8) return; // small movement ignored
      const now = Date.now();
      // If scrolling down and passed a minimal offset, hide and keep hidden while continuing to scroll down
      if (current > lastScroll && current > 60) {
        // ensure we don't spam toggles
        if (!navbar.classList.contains('hidden')) {
          navbar.classList.add('hidden');
          lastHideTs = now;
        }
      } else if (current < lastScroll) {
        // scrolling up -> reveal navbar
        if (navbar.classList.contains('hidden')) navbar.classList.remove('hidden');
      }
      lastScroll = current;
      ticking = false;
    };
    window.addEventListener('scroll', () => {
      if (!ticking) {
        window.requestAnimationFrame(handleNavbarAutoHide);
        ticking = true;
      }
    }, { passive: true });
    // On load ensure navbar state is sane: remove .hidden if at top, set lastScroll, and apply scrolled state.
    window.addEventListener('load', () => {
      lastScroll = window.scrollY || 0;
      // if near top, make sure navbar visible
      if (lastScroll < 60) {
        navbar?.classList.remove('hidden');
      }
      // run setNavbarBg after small timeout to avoid layout shift races
      setTimeout(setNavbarBg, 50);
    });

  // Also call once immediately after DOM ready to set initial state
  setNavbarBg();

  /* Theme toggle (light/dark) initialization and persistence
     Default: light mode (unchecked). The toggle input was inserted into the navbar.
  */
  const themeToggle = document.getElementById('themeToggle');
  const applyTheme = (t) => {
    if(!t) t = 'light';
    document.body.setAttribute('data-theme', t);
    // ensure toggle input reflects state (checked = dark)
    if(themeToggle) themeToggle.checked = (t === 'dark');
    try { localStorage.setItem('padi-theme', t); } catch(e){}
  };

  // restore saved theme or default to 'light'
  try {
    const saved = localStorage.getItem('padi-theme') || 'light';
    applyTheme(saved);
  } catch(e){ applyTheme('light'); }

  if(themeToggle){
    themeToggle.addEventListener('change', (e)=>{
      const t = e.target.checked ? 'dark' : 'light';
      applyTheme(t);
    });
  }

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

  // Get products from shared ProductsManager
  let products = ProductsManager.getProducts().map(p => ({
    ...p,
    img: p.image,
    sellerPhone: p.phone
  }));

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
    const cat = (categoryFilter?.value || 'all').toLowerCase();
    const sort = sortSelect?.value || 'popular';
    let out = products.filter(p => {
      const title = (p.title || '').toString().toLowerCase();
      const category = (p.category || '').toString().toLowerCase();
      const matchQ = !q || title.includes(q) || category.includes(q);
      // match category in a tolerant way: 'all' or category includes selected value
      const matchCat = (cat === 'all') || category.includes(cat);
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

  // Listen for product updates from admin page
  window.addEventListener('productsUpdated', (e) => {
    products = e.detail.map(p => ({
      ...p,
      img: p.image,
      sellerPhone: p.phone
    }));
    applyFilters();
  });

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

  // Scroll indicator: smooth-scroll to next section (#about)
  const scrollIndicator = document.querySelector('.scroll-indicator');
  if(scrollIndicator){
    scrollIndicator.style.cursor = 'pointer';
    scrollIndicator.setAttribute('role','button');
    scrollIndicator.addEventListener('click', (e) => {
      const target = document.getElementById('about') || document.getElementById('products') || document.body;
      if(target && target.scrollIntoView) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  // Accessibility: close menu with ESC
  document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') closeMenu(); });
});