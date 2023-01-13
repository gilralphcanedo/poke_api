<template>
  <div class="card card--rounded pokemon-card" @click="openPokemonDetail">
    <div class="card-body">
      <div :class="['favorite', pokemon.is_favorite ? 'favorited' : '']" @click="toggleAction">
        <i data-feather="star" width="22" height="22"></i>
      </div>
      <img class="img-fluid image" :src="pokemon.src">
      <p class="id">{{paddedZerosID(pokemon.id)}}</p>
      <p class="name">{{pokemon.name}}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: ['pokemon'],
  data() {
    return {

    }
  },
  methods: {
    openPokemonDetail() {
      this.$emit('openPokemonDetail', this.pokemon)
    },
    paddedZerosID(id) {
      const maxDigits = 4
      return '#' + String(id).padStart(maxDigits, '0');
    },
    toggleAction() {
      this.pokemon.is_favorite = !this.pokemon.is_favorite ? 1 : 0
      const actions = [
        {
          action: 'like',
          active: 0,
        },
        {
          action: 'hate',
          active: 0,
        },
        {
          action: 'favorite',
          active: this.pokemon.is_favorite,
        }
      ]
      this.setPokemonAsFavorite(actions)
    },
    async setPokemonAsFavorite(actions) {
      const res = await axios.post('actions/set', { actions, pokemon_id: this.pokemon.id })
    }
  },
}
</script>

<style>

</style>