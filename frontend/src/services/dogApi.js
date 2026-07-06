import axios from "axios"

const dogApi = axios.create({
  baseURL: "https://api.thedogapi.com/v1",
  headers: {
    "x-api-key": "live_py6ttqe0iliVSZaLvZqjdND6PcPsQtNSiJGGF4Iyr7YvPS7gMqebprs8i8gX6qn6"
  }
})

export async function getRandomDogImage() {
  const res = await dogApi.get("/images/search")
  return res.data[0].url
}