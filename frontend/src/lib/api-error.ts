/**
 * Erreur métier renvoyée par l'API Laravel.
 */
export class ApiError extends Error {
  status: number;
  fieldErrors: Record<string, string[]>;

  /**
   * @param message Message lisible
   * @param status Code HTTP
   * @param fieldErrors Erreurs de validation par champ
   */
  constructor(message: string, status: number, fieldErrors: Record<string, string[]> = {}) {
    super(message);
    this.name = "ApiError";
    this.status = status;
    this.fieldErrors = fieldErrors;
  }
}
