import axios from "axios"

const dogApi = axios.create({
  baseURL: "https://dog.ceo/api"
})

export async function getRandomDogImage() {
  const res = await dogApi.get("/breeds/image/random")
  return res.data.message
}