/**
 * Réponse standard de l'API Laravel AddressImmo.
 */
export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

/**
 * Métadonnées de pagination catalogue.
 */
export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

/**
 * Liste paginée de produits.
 */
export interface ProductListData {
  items: Product[];
  pagination: PaginationMeta;
}

/**
 * Fichier média lié à une annonce.
 */
export interface ProductFile {
  id: number;
  file_name: string;
  file_url: string;
  file_type: string;
}

/**
 * Annonce immobilière.
 */
export interface Product {
  id: number;
  product_name: string;
  product_description: string;
  quantity: number;
  price: string;
  converted_price: string;
  readable_currency: string;
  action: string;
  readable_action: string;
  country: string;
  city: string;
  address: string;
  municipality: string;
  neighborhood: string;
  street: string;
  type: string;
  readable_type: string;
  photos: ProductFile[];
  average_rating: number;
  created_at: string;
  created_at_explicit: string;
}
