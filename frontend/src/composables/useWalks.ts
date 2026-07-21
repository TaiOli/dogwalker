import { ref } from "vue";
import { api } from "../services/api";

export function useWalks() {
  const tours = ref([]);

  async function loadTours() {
    const response = await api.get("/tours");
    tours.value = response.data;
  }

  async function tourAccept(id: string | number | null) {
    await api.put(`/tours/${id}/accept`);
  }

  async function tourReject(id: string | number | null) {
    await api.patch(`/tours/${id}/reject`);
  }

  return {
    tours,
    loadTours,
    tourAccept,
    tourReject,
  };
}