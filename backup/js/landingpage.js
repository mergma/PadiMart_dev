document.addEventListener('DOMContentLoaded', () => {
  // format harga
  const formatPrice = v => v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

  // Responsive
  const isMobile = () => window.innerWidth <= 768;
  const isTablet = () => window.innerWidth > 768 && window.innerWidth <= 1024;
  const isDesktop = () => window.innerWidth > 1024;

  // klo device hp
  const isTouchDevice = () => {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
  };

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
    // navbar hide on scrol
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

  // Enhanced side menu handlers with better mobile support
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sideMenu = document.getElementById('sideMenu');
  const sideMenuOverlay = document.getElementById('sideMenuOverlay');
  const closeMenuBtn = document.getElementById('closeMenuBtn');

  const openMenu = () => {
    if(sideMenu){
      sideMenu.classList.add('active');
      sideMenuOverlay?.classList.add('active');
      sideMenu.setAttribute('aria-hidden','false');
      document.body.style.overflow='hidden';
      // Focus management for accessibility
      setTimeout(() => closeMenuBtn?.focus(), 100);
    }
  };

  const closeMenu = () => {
    if(sideMenu){
      sideMenu.classList.remove('active');
      sideMenuOverlay?.classList.remove('active');
      sideMenu.setAttribute('aria-hidden','true');
      document.body.style.overflow='';
      // Return focus to hamburger button
      hamburgerBtn?.focus();
    }
  };

  if(hamburgerBtn) {
    hamburgerBtn.addEventListener('click', openMenu);
    // better mobile response ( i hope loll)
    hamburgerBtn.addEventListener('touchstart', (e) => {
      e.preventDefault();
      openMenu();
    }, { passive: false });
  }

  if(closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);
  if(sideMenuOverlay) sideMenuOverlay.addEventListener('click', closeMenu);
  document.querySelectorAll('.side-menu a').forEach(a => a.addEventListener('click', closeMenu));

  // Get products from PHP backend
  let products = [];

  // Load products from database via PHP
  async function loadProductsFromDatabase() {
    try {
      const apiUrl = window.CONFIG?.API_BASE_URL || '/padi/api';
      const response = await fetch(apiUrl + '/get-products.php');
      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const result = await response.json();
      if (!result.success) throw new Error(result.message || 'Failed to load products');

      products = (result.data || []).map(p => ({
        ...p,
        img: p.image,
        sellerPhone: p.phone
      }));

      console.log('Loaded', products.length, 'products from database');
      applyFilters();
    } catch (error) {
      console.error('Error loading products from database:', error);
      // Fallback to localStorage if database fails
      products = ProductsManager.getProducts().map(p => ({
        ...p,
        img: p.image,
        sellerPhone: p.phone
      }));
      applyFilters();
    }
  }

  // Load products on page load
  loadProductsFromDatabase();

  // Debug function - available in console
  window.debugProducts = () => {
    console.log('Current products:', products);
    console.log('Raw products from ProductsManager:', ProductsManager.getProducts());
    console.log('LocalStorage products:', JSON.parse(localStorage.getItem('padimart_products') || '[]'));
  };

  // Debug edit system - available in console
  window.debugEditSystem = () => {
    const cards = document.querySelectorAll('.product-card');
    console.log('Total product cards:', cards.length);

    cards.forEach((card, index) => {
      const editBtn = card.querySelector('.card__edit-btn');
      const editForm = card.querySelector('.card__edit-form');
      const title = card.querySelector('.card__title')?.textContent;

      console.log(`Card ${index + 1} (${title}):`, {
        hasEditBtn: !!editBtn,
        hasEditForm: !!editForm,
        isExpanded: card.classList.contains('expanded')
      });
    });
  };

  // Function to refresh products - available in console
  window.refreshProducts = async () => {
    await loadProductsFromDatabase();
    console.log('Products refreshed:', products.length, 'products loaded');
  };

  // Function to reset to defaults - available in console
  window.resetProductsToDefaults = async () => {
    await loadProductsFromDatabase();
    console.log('Products reset to defaults:', products.length, 'products loaded');
  };

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

      // Format image URL
      let imageUrl = p.img || p.image || '';

      // If no image, use placeholder
      if (!imageUrl) {
        imageUrl = 'img/placeholder-product.png';
      }
      // If it's a file path (starts with uploads/), use it directly
      else if (imageUrl.startsWith('uploads/')) {
        imageUrl = imageUrl; // Use as-is
      }
      // If it's base64 without data URI prefix, add it
      else if (imageUrl && !imageUrl.startsWith('data:') && !imageUrl.startsWith('http') && !imageUrl.startsWith('uploads/')) {
        imageUrl = 'data:image/jpeg;base64,' + imageUrl;
      }

      card.innerHTML = `
        <div class="card__shine"></div>
        <div class="card__glow"></div>
        ${badge}
        <div class="card__content">
          <div class="card__image" style="background-image:url('${imageUrl}');"></div>
          <div class="card__text">
            <p class="card__title">${p.title}</p>
            <p class="card__description">${p.product_description || p.category || ''}</p>
          </div>
          <div class="card__footer">
            <div class="card__price">Rp ${formatPrice(p.price)}</div>
            <button class="card__button" data-phone="${p.sellerPhone}" aria-label="Chat ${p.title}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a4 4 0 0 1-4 4H7l-4 4V5a2 2 0 0 1 2-2h14a4 4 0 0 1 4 4z"></path></svg>
            </button>
          </div>
        </div>
      `;

      // Card click handler -> Open modal
      card.addEventListener('click', (e) => {
        // Don't open modal if clicking the WhatsApp button
        if (e.target.closest('.card__button')) return;
        openProductModal(p);
      });

      // button handler -> WhatsApp
      const btn = card.querySelector('.card__button');
      if(btn){
        btn.addEventListener('click', (e) => {
          e.stopPropagation(); // Prevent card click
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

  // Testimonials are now displayed as a grid - no slider functionality needed

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

  // Product Modal Functionality
  const productModal = document.getElementById('productModal');
  const modalOverlay = productModal?.querySelector('.modal-overlay');
  const modalClose = productModal?.querySelector('.modal-close');
  const modalWhatsappBtn = document.getElementById('modalWhatsappBtn');
  const modalShareBtn = document.getElementById('modalShareBtn');

  // Modal elements
  const modalImage = document.getElementById('modalImage');
  const modalBadge = document.getElementById('modalBadge');
  const modalTitle = document.getElementById('modalTitle');
  const modalCategory = document.getElementById('modalCategory');
  const modalPrice = document.getElementById('modalPrice');
  const modalDescription = document.getElementById('modalDescription');
  const modalDetailCategory = document.getElementById('modalDetailCategory');
  const modalWeight = document.getElementById('modalWeight');
  const modalOrigin = document.getElementById('modalOrigin');
  const modalCondition = document.getElementById('modalCondition');
  const modalSellerName = document.getElementById('modalSellerName');
  const modalSellerLocation = document.getElementById('modalSellerLocation');

  let currentProduct = null;

  // Open modal function
  function openProductModal(product) {
    if (!productModal) return;

    currentProduct = product;

    // Update modal content
    if (modalImage) {
      let imageUrl = product.img || product.image || '';

      // If no image, use placeholder
      if (!imageUrl) {
        imageUrl = 'img/placeholder-product.png';
      }
      // If it's a file path (starts with uploads/), use it directly
      else if (imageUrl.startsWith('uploads/')) {
        imageUrl = imageUrl; // Use as-is
      }
      // If it's base64 without data URI prefix, add it
      else if (imageUrl && !imageUrl.startsWith('data:') && !imageUrl.startsWith('http') && !imageUrl.startsWith('uploads/')) {
        imageUrl = 'data:image/jpeg;base64,' + imageUrl;
      }

      modalImage.style.backgroundImage = `url('${imageUrl}')`;

      // Add fallback for broken images
      const img = new Image();
      img.onload = () => {
        modalImage.style.backgroundImage = `url('${imageUrl}')`;
      };
      img.onerror = () => {
        modalImage.style.backgroundImage = `url('https://via.placeholder.com/600x420?text=${encodeURIComponent(product.title || 'Produk')}')`;
      };
      img.src = imageUrl;
    }

    if (modalBadge) {
      if (product.popular) {
        modalBadge.style.display = 'block';
      } else {
        modalBadge.style.display = 'none';
      }
    }

    if (modalTitle) modalTitle.textContent = product.title || 'Nama Produk';
    if (modalCategory) modalCategory.textContent = product.category || 'Kategori';
    if (modalPrice) modalPrice.textContent = `Rp ${formatPrice(product.price || 0)}`;
    if (modalDetailCategory) modalDetailCategory.textContent = product.category || '-';

    // Generate description based on product data
    const description = generateProductDescription(product);
    if (modalDescription) modalDescription.textContent = description;

    // Set product details from data or defaults
    if (modalWeight) modalWeight.textContent = product.weight || '1 kg';
    if (modalOrigin) modalOrigin.textContent = product.origin || 'Tabalong, Kalimantan Selatan';
    if (modalCondition) modalCondition.textContent = product.condition || 'Baru';
    if (modalSellerName) modalSellerName.textContent = product.seller || 'Petani Lokal';
    
    // Set seller location - use seller_location if available, otherwise use product origin
    if (modalSellerLocation) {
      modalSellerLocation.textContent = product.seller_location || product.origin || 'Tabalong, Kalimantan Selatan';
    }

    // Show modal
    productModal.classList.add('active');
    productModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  // Close modal function
  function closeProductModal() {
    if (!productModal) return;

    productModal.classList.remove('active');
    productModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    currentProduct = null;
  }

  // Generate product description
  function generateProductDescription(product) {
    // If product has a description from database, use it
    if (product.product_description) {
      return product.product_description;
    }

    // Otherwise, fall back to category-based descriptions
    const categoryDescriptions = {
      'Beras': 'Beras berkualitas tinggi dari petani lokal Tabalong. Diproduksi dengan metode tradisional yang terjaga kualitasnya, memberikan rasa dan aroma yang khas. Cocok untuk konsumsi sehari-hari keluarga.',
      'Camilan & Olahan': 'Camilan tradisional yang dibuat dengan resep turun temurun. Menggunakan bahan-bahan alami pilihan dari daerah setempat, memberikan cita rasa autentik yang tak terlupakan.',
      'Kerajinan & Oleh-oleh': 'Kerajinan tangan berkualitas tinggi yang dibuat oleh pengrajin lokal. Setiap produk memiliki keunikan tersendiri dan mencerminkan kearifan budaya lokal Kalimantan Selatan.',
      'Pupuk': 'Pupuk organik berkualitas tinggi yang ramah lingkungan. Diolah dari bahan-bahan alami untuk mendukung pertanian berkelanjutan dan meningkatkan kesuburan tanah.',
      'Benih': 'Benih unggul dengan kualitas terjamin. Telah melalui proses seleksi ketat untuk menghasilkan tanaman yang produktif dan tahan terhadap berbagai kondisi cuaca.',
      'Alat': 'Peralatan pertanian berkualitas yang dirancang untuk memudahkan pekerjaan petani. Terbuat dari bahan yang tahan lama dan mudah digunakan.',
      'Edukasi': 'Paket panduan lengkap untuk meningkatkan pengetahuan dan keterampilan dalam bidang pertanian. Disusun oleh ahli berpengalaman dengan bahasa yang mudah dipahami.',
      'Olahan': 'Produk olahan berkualitas tinggi yang diproses dengan teknologi modern namun tetap mempertahankan cita rasa tradisional. Higienis dan bergizi tinggi.'
    };

    return categoryDescriptions[product.category] || 'Produk berkualitas tinggi dari petani dan pengrajin lokal Tabalong, Kalimantan Selatan. Diproduksi dengan standar kualitas terbaik untuk memenuhi kebutuhan Anda.';
  }

  // Event listeners for modal
  if (modalClose) {
    modalClose.addEventListener('click', closeProductModal);
  }

  if (modalOverlay) {
    modalOverlay.addEventListener('click', closeProductModal);
  }

  if (modalWhatsappBtn) {
    modalWhatsappBtn.addEventListener('click', () => {
      if (currentProduct && currentProduct.sellerPhone) {
        const phone = currentProduct.sellerPhone.replace(/\D/g, '');
        const message = `Halo, saya tertarik dengan produk ${currentProduct.title}. Bisakah Anda memberikan informasi lebih lanjut?`;
        const encodedMessage = encodeURIComponent(message);
        window.open(`https://wa.me/${phone}?text=${encodedMessage}`, '_blank', 'noopener');
      }
    });
  }

  if (modalShareBtn) {
    modalShareBtn.addEventListener('click', () => {
      if (currentProduct && navigator.share) {
        navigator.share({
          title: currentProduct.title,
          text: `Lihat produk ${currentProduct.title} di PadiMart`,
          url: window.location.href
        }).catch(console.error);
      } else if (currentProduct) {
        // Fallback: copy to clipboard
        const shareText = `Lihat produk ${currentProduct.title} di PadiMart: ${window.location.href}`;
        navigator.clipboard.writeText(shareText).then(() => {
          alert('Link produk telah disalin ke clipboard!');
        }).catch(() => {
          alert('Tidak dapat menyalin link. Silakan salin manual dari address bar.');
        });
      }
    });
  }

  // Make openProductModal globally available
  window.openProductModal = openProductModal;

  // Accessibility: close modal with ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeMenu();
      closeProductModal();
    }
  });
});