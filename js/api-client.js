/**
 * API Client for PADI Mart Database
 * Handles all communication with PHP backend
 */

const APIClient = {
  baseURL: '/padi/api',

  /**
   * Get all products from database
   */
  async getAllProducts() {
    try {
      const response = await fetch(`${this.baseURL}/products.php?action=all`);
      const data = await response.json();
      if (data.success) {
        return data.data;
      } else {
        throw new Error(data.message || 'Failed to fetch products');
      }
    } catch (error) {
      console.error('Error fetching products:', error);
      throw error;
    }
  },

  /**
   * Get single product by ID
   */
  async getProduct(id) {
    try {
      const response = await fetch(`${this.baseURL}/products.php?action=single&id=${id}`);
      const data = await response.json();
      if (data.success) {
        return data.data;
      } else {
        throw new Error(data.message || 'Product not found');
      }
    } catch (error) {
      console.error('Error fetching product:', error);
      throw error;
    }
  },

  /**
   * Add new product
   */
  async addProduct(product) {
    try {
      const response = await fetch(`${this.baseURL}/products.php`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(product)
      });
      const data = await response.json();
      if (data.success) {
        return data;
      } else {
        throw new Error(data.message || 'Failed to add product');
      }
    } catch (error) {
      console.error('Error adding product:', error);
      throw error;
    }
  },

  /**
   * Update product
   */
  async updateProduct(id, updates) {
    try {
      const response = await fetch(`${this.baseURL}/products.php`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id, ...updates })
      });
      const data = await response.json();
      if (data.success) {
        return data;
      } else {
        throw new Error(data.message || 'Failed to update product');
      }
    } catch (error) {
      console.error('Error updating product:', error);
      throw error;
    }
  },

  /**
   * Delete product
   */
  async deleteProduct(id) {
    try {
      const response = await fetch(`${this.baseURL}/products.php`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id })
      });
      const data = await response.json();
      if (data.success) {
        return data;
      } else {
        throw new Error(data.message || 'Failed to delete product');
      }
    } catch (error) {
      console.error('Error deleting product:', error);
      throw error;
    }
  }
};

