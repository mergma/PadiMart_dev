// Shared products data storage
// This file manages products data that syncs between admin and index pages using localStorage

const ProductsManager = {
  // Default products
  defaultProducts: [
    {
      id: 1,
      title: 'Beras Organik Premium',
      category: 'Beras',
      price: 120000,
      image: 'https://via.placeholder.com/600x420?text=Beras+Organik',
      popular: true,
      phone: '+628123456789',
      created: '2024-09-01',
      weight: '5 kg',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Petani Beras Organik'
    },
    {
      id: 2,
      title: 'Pupuk Organik Cair',
      category: 'Pupuk',
      price: 45000,
      image: 'https://via.placeholder.com/600x420?text=Pupuk+Organik',
      popular: false,
      phone: '+628987654321',
      created: '2024-08-15',
      weight: '1 liter',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Koperasi Tani Maju'
    },
    {
      id: 3,
      title: 'Benih Padi Unggul',
      category: 'Benih',
      price: 75000,
      image: 'https://via.placeholder.com/600x420?text=Benih+Padi',
      popular: true,
      phone: '+628112233445',
      created: '2024-10-10',
      weight: '2 kg',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Balai Benih Unggul'
    },
    {
      id: 4,
      title: 'Alat Pemipil Padi',
      category: 'Alat',
      price: 250000,
      image: 'https://via.placeholder.com/600x420?text=Alat+Pemipil',
      popular: false,
      phone: '+628556677889',
      created: '2024-07-20',
      weight: '3 kg',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Bengkel Alat Tani'
    },
    {
      id: 5,
      title: 'Paket Panduan Budidaya',
      category: 'Edukasi',
      price: 150000,
      image: 'https://via.placeholder.com/600x420?text=Paket+Panduan',
      popular: true,
      phone: '+628667788990',
      created: '2024-06-05',
      weight: '500 gram',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Pusat Edukasi Pertanian'
    },
    {
      id: 6,
      title: 'Gandum Olahan',
      category: 'Camilan & Olahan',
      price: 90000,
      image: 'https://via.placeholder.com/600x420?text=Gandum+Olahan',
      popular: false,
      phone: '+628223344556',
      created: '2024-05-01',
      weight: '2 kg',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Industri Olahan Lokal'
    },
    {
      id: 7,
      title: 'Kerajinan Anyaman Bambu',
      category: 'Kerajinan & Oleh-oleh',
      price: 75000,
      image: 'https://via.placeholder.com/600x420?text=Anyaman+Bambu',
      popular: true,
      phone: '+628334455667',
      created: '2024-11-01',
      weight: '500 gram',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Pengrajin Bambu Lokal'
    },
    {
      id: 8,
      title: 'Keripik Singkong Pedas',
      category: 'Camilan & Olahan',
      price: 25000,
      image: 'https://via.placeholder.com/600x420?text=Keripik+Singkong',
      popular: true,
      phone: '+628445566778',
      created: '2024-10-15',
      weight: '250 gram',
      origin: 'Tabalong, Kalimantan Selatan',
      condition: 'Baru',
      seller: 'Usaha Keripik Mama'
    }
  ],

  storageKey: 'padimart_products',

  // Initialize products from localStorage or use defaults
  init() {
    const stored = localStorage.getItem(this.storageKey);
    if (!stored) {
      this.saveProducts(this.defaultProducts);
    }
    return this.getProducts();
  },

  // Get all products
  getProducts() {
    const stored = localStorage.getItem(this.storageKey);
    const products = stored ? JSON.parse(stored) : this.defaultProducts;

    // Migrate existing products to include new fields
    return products.map(product => ({
      ...product,
      weight: product.weight || '1 kg',
      origin: product.origin || 'Tabalong, Kalimantan Selatan',
      condition: product.condition || 'Baru',
      seller: product.seller || 'Petani Lokal'
    }));
  },

  // Save products to localStorage
  saveProducts(products) {
    localStorage.setItem(this.storageKey, JSON.stringify(products));
    // Dispatch custom event to notify same page
    window.dispatchEvent(new CustomEvent('productsUpdated', { detail: products }));
  },

  // Add a product
  addProduct(product) {
    const products = this.getProducts();
    const newId = Math.max(...products.map(p => p.id), 0) + 1;
    const newProduct = {
      ...product,
      id: newId,
      created: new Date().toISOString().split('T')[0]
    };
    products.push(newProduct);
    this.saveProducts(products);
    return newProduct;
  },

  // Update a product
  updateProduct(id, updates) {
    const products = this.getProducts();
    const index = products.findIndex(p => p.id === id);
    if (index !== -1) {
      products[index] = { ...products[index], ...updates };
      this.saveProducts(products);
      return products[index];
    }
    return null;
  },

  // Delete a product
  deleteProduct(id) {
    const products = this.getProducts();
    const filtered = products.filter(p => p.id !== id);
    this.saveProducts(filtered);
  },

  // Get product by ID
  getProductById(id) {
    const products = this.getProducts();
    return products.find(p => p.id === id);
  },

  // Reset to default products
  resetToDefaults() {
    this.saveProducts(this.defaultProducts);
  }
};

// Initialize on load
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    ProductsManager.init();
  });
} else {
  ProductsManager.init();
}

// Listen for storage changes from other tabs/windows
window.addEventListener('storage', (e) => {
  if (e.key === ProductsManager.storageKey) {
    // Products were updated in another tab, dispatch event on this page
    const updatedProducts = JSON.parse(e.newValue || '[]');
    window.dispatchEvent(new CustomEvent('productsUpdated', { detail: updatedProducts }));
  }
});

