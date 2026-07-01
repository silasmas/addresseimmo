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
 * Rôle utilisateur AddressImmo.
 */
export interface UserRole {
  id: number;
  role_name: string;
  role_description?: string;
}

/**
 * Profil utilisateur renvoyé par l'API auth.
 */
export interface User {
  id: number;
  firstname: string;
  lastname: string | null;
  surname: string | null;
  fullname: string;
  email: string | null;
  phone: string | null;
  username: string | null;
  avatar_url: string;
  country: string | null;
  city: string | null;
  currency: string;
  readable_currency: string | null;
  status: string;
  selected_role: UserRole | null;
  roles: UserRole[];
  average_rating: number;
  created_at: string;
}

/**
 * Données renvoyées après login ou inscription.
 */
export interface AuthData {
  user: User;
  token: string;
  token_type: string;
}

/**
 * Réponse après envoi d'un OTP (connexion ou inscription).
 */
export interface OtpDeliveryData {
  requires_otp: boolean;
  login: string;
  channel: string;
  masked_contact: string;
  debug_otp?: string | null;
}

/**
 * Statut de déploiement backend exposé au frontend.
 */
export interface InstallStatus {
  installed: boolean;
  requirements_ok: boolean;
  database_connected: boolean;
  migrations_pending: number;
  has_admin: boolean;
  frontend_ready: boolean;
  version: string;
  install_url: string;
  admin_url: string;
}

/**
 * Payload d'inscription API.
 */
export interface RegisterPayload {
  firstname: string;
  lastname?: string;
  email?: string;
  phone?: string;
  username?: string;
  password: string;
  password_confirmation: string;
  currency?: "USD" | "CDF";
  country?: string;
  city?: string;
}

/**
 * Payload de connexion API.
 */
export interface LoginPayload {
  login: string;
  password: string;
  device_name?: string;
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
