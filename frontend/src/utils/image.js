export const getPhoto = (foto) => {
  if (!foto) return "https://placehold.co/120x120"

  if (foto.startsWith("http")) return foto

  return `http://localhost:8000/storage/${foto}`
}