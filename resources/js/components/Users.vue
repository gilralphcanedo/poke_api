<template>
  <div class="container">
    <h1 class="text-white font-weight-bold mb-4">Users Page</h1>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive mt-3">
          <table class="table">
            <thead>
              <tr>
                <th>User</th>
                <th>Favorites</th>
                <th>Likes</th>
                <th>Hates</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(user, index) in users" :key="index">
                <td>{{user.name}}</td>
                <td>
                  <img class="pokemon-thumb" :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${favorited.pokemon_id}.png`" v-for="(favorited, favoritesIndex) in filterFavorites(user.like_hate_favorites)" :key="favoritesIndex">
                </td>
                <td>
                  <img class="pokemon-thumb" :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${liked.pokemon_id}.png`" v-for="(liked, likesIndex) in filterLikes(user.like_hate_favorites)" :key="likesIndex">
                </td>
                <td>
                  <img class="pokemon-thumb" :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${hated.pokemon_id}.png`" v-for="(hated, hatesIndex) in filterHates(user.like_hate_favorites)" :key="hatesIndex">
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
    }
  },
  created() {
    this.getLikesHatesFavorites()
  },
  methods: {
    async getLikesHatesFavorites() {
      const res = await axios.get('other-users')
      this.users = res.data
    },
    filterLikes(arr) {
      return arr.filter(ojb => ojb.like == 1)
    },
    filterHates(arr) {
      return arr.filter(ojb => ojb.hate == 1)
    },
    filterFavorites(arr) {
      return arr.filter(ojb => ojb.favorite == 1)
    },
  }
}
</script>

<style lang="scss" scoped>
.table td {
  vertical-align: middle;
}
.pokemon-thumb {
    width: 32px;
    background: darkgray;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #4c4c4c;
    width: 32px;
    background: darkgray;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 3px;
    border: 1px solid #4c4c4c;
    box-shadow: rgb(0 0 0 / 15%) 1.95px 1.95px 2.6px;
    &:not(:last-child) {
      margin-right: 3px;
    }
}
</style>