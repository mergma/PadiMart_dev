/**
 * PHP API Client - Fetch products from PHP backend
 * Works with /api/get-products.php and /api/manage-products.php
 */

const PHPAPIClient = {
    baseUrl: '/padi/api',
    
    /**
     * Get all products with optional filters
     */
    async getAllProducts(filters = {}) {
        try {
            const params = new URLSearchParams();
            if (filters.category) params.append('category', filters.category);
            if (filters.search) params.append('search', filters.search);
            if (filters.sort) params.append('sort', filters.sort);
            
            const url = `${this.baseUrl}/get-products.php${params.toString() ? '?' + params : ''}`;
            const response = await fetch(url);
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Failed to fetch products');
            
            return result.data || [];
        } catch (error) {
            console.error('Error fetching products:', error);
            return [];
        }
    },
    
    /**
     * Add new product
     */
    async addProduct(formData) {
        try {
            const response = await fetch(`${this.baseUrl}/manage-products.php`, {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Failed to add product');
            
            return result;
        } catch (error) {
            console.error('Error adding product:', error);
            throw error;
        }
    },
    
    /**
     * Update product
     */
    async updateProduct(id, formData) {
        try {
            formData.append('id', id);
            
            const response = await fetch(`${this.baseUrl}/manage-products.php`, {
                method: 'PUT',
                body: formData
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Failed to update product');
            
            return result;
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
            const formData = new FormData();
            formData.append('id', id);
            
            const response = await fetch(`${this.baseUrl}/manage-products.php`, {
                method: 'DELETE',
                body: formData
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Failed to delete product');
            
            return result;
        } catch (error) {
            console.error('Error deleting product:', error);
            throw error;
        }
    }
};

