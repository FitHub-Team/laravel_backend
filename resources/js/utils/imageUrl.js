const STORAGE_URL = import.meta.env.VITE_STORAGE_URL || "/storage";

export function getImageUrl(path, fallback = "") {
  if (!path) return fallback;

  // Already a complete URL
  if (/^https?:\/\//i.test(path)) {
    return path;
  }

  // Blob or Base64 image
  if (/^(blob:|data:)/i.test(path)) {
    return path;
  }

  // Normalize relative paths
  const cleanPath = String(path).replace(/^\/+/, "");

  // Avoid duplicating /storage
  if (cleanPath.startsWith("storage/")) {
    return `/${cleanPath}`;
  }

  return `${STORAGE_URL.replace(/\/+$/, "")}/${cleanPath}`;
}