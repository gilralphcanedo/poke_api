<template>
  <div>
    <div class="d-md-flex align-items-md-center justify-content-md-between mb-4">
      <input type="search" class="form-control pokemon-search" placeholder="Search pokemon..." v-model="search" @change="searchPokemon()">
      <router-link to="/users" class="btn btn-main">Discover Trainers</router-link>
    </div>

    <div class="row">
      <loading-spinner class="force-center" v-show="isLoadingPokemons"/>
      <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3" v-for="(pokemon, index) in pokemons" :key="index">
        <pokemon-card :pokemon="pokemon" @openPokemonDetail="openPokemonDetail"/>
      </div>
    </div>

    <div class="text-center mt-4">
      <button class="btn btn-main btn-lg mr-2" @click="prevPage()" :disabled="isLoadingPokemons">Prev</button>
      <button class="btn btn-main btn-lg" @click="nextPage()" :disabled="isLoadingPokemons">Next</button>
    </div>
    
    <!-- pokemon details modal -->
    <div class="modal fade pokemon-detail" id="pokemonDetailModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <loading-spinner v-show="isLoading"/>
            <div v-show="!isLoading">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closePokemonDetail()">
                <span aria-hidden="true">&times;</span>
              </button>

              <!-- Image -->
              <img class="img-fluid image" :src="pokemonDetail.src">

              <!-- Name -->
              <h4 class="name mb-3">{{pokemonDetail.name}}</h4>

              <!-- Type -->
              <div class="type mb-4">
                <span :class="['badge', `badge-${type.type.name}`, 'pokemon-type']" v-for="(type, typeIndex) in pokemonDetail.types" :key="typeIndex">{{type.type.name}}</span>
              </div>

              <!-- Like or Hate -->
              <like-hate-button :pokemon_id="pokemonDetail.id"/>

              <!-- Height and Weight -->
              <div class="d-flex align-items-center justify-content-center mb-4">
                <div class="text-center mr-3">
                  <span class="weight">{{pokemonDetail.weight | hectogramsToKilograms}} ({{pokemonDetail.weight | hectogramsToPounds}})</span>
                  <p class="mb-0">Weight</p>
                </div>
                <div class="text-center">
                  <span class="height">{{pokemonDetail.height | decimetersToCentimeters}} ({{pokemonDetail.height | decimetersToFeetAndInch}})</span>
                  <p class="mb-0">Height</p>
                </div>
              </div>

              <!-- Base Stats -->
              <div class="stats-wrapper">
                <h5 class="ml-2">Base Stats</h5>
                <table class="table table-stats">
                  <tbody>
                    <tr v-for="(stat, statsIndex) in pokemonDetail.stats" :key="statsIndex">
                      <td class="stat-name">{{stat.stat.name | prettifyStatName}}</td>
                      <td class="stat-value">{{stat.base_stat}}</td>
                      <td class="d-none d-sm-table-cell">
                        <div :class="['progress progress-stat', stat.stat.name]">
                          <div class="progress-bar"
                          role="progressbar"
                          :style="`width: ${(stat.base_stat / getMaxStat(stat.stat.name, stat.base_stat) * 100)}%`"
                          :aria-valuenow="stat.base_stat"
                          aria-valuemin="0"
                          :aria-valuemax="getMaxStat(stat.stat.name, stat.base_stat)"></div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import LoadingSpinner from './LoadingSpinner.vue'
import PokemonCard from './PokemonCard.vue'
import LikeHateButton from './LikeHateButton.vue'

export default {
components : { LoadingSpinner, PokemonCard, LikeHateButton },
data() {
  return {
    isLoading: false,
    isLoadingPokemons: false,
    pokemons: [],
    pokemonCount: 0,
    pokemonDetail: {
      height: '',
      id: '',
      moves: [],
      name: '',
      src: '',
      stats: [],
      types: [],
      weight: '',
    },
    search: '',
    prevPageLink: '',
    nextPageLink: '',
  }
},
 created() {
  this.getPokemons()
},
mounted() {
  
},
methods: {
  async getPokemons (url = 'https://pokeapi.co/api/v2/pokemon?limit=18') {
    this.isLoadingPokemons = true
    const res = await axios.get(url);
    
    this.prevPageLink = res.data.previous
    this.nextPageLink = res.data.next
    this.pokemons = res.data.results.map(obj => ({ ...obj, id: parseInt(this.getPokemonIdByUrl(obj.url)), src: this.getPokemonArtWorkById(parseInt(this.getPokemonIdByUrl(obj.url))), is_favorite: 0 }))
    this.renderFeatherIcons()
    this.setFavoritePokemons()
    this.isLoadingPokemons = false
  },
  async searchPokemon() {
    if(!this.search.length) {
      this.getPokemons()
      return
    }

    this.isLoadingPokemons = true
    let res

    try {
      res = await axios.get(`https://pokeapi.co/api/v2/pokemon/${this.search}`)
    } catch (error) {
        await swal("Oops!",`Pokemon ${error.response.data}`, "error")
        this.isLoadingPokemons = false
      return
    }

    const pokemon_detail = res.data

    this.pokemons = [
      {
        id: pokemon_detail.id,
        is_favorite: 0,
        name: pokemon_detail.name,
        src: `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${pokemon_detail.id}.png`,
        url: `https://pokeapi.co/api/v2/pokemon/${pokemon_detail.id}`,
      }
    ]

    this.prevPageLink = res.data.previous
    this.nextPageLink = res.data.next

    this.renderFeatherIcons()
    this.setFavoritePokemons()

    setTimeout(() => {
      this.isLoadingPokemons = false
    }, 300);

  },
  async getFavoritedPokemons() {
    const res = await axios.get('favorites')
    return res.data
  },
  async setFavoritePokemons() {
    const faves = await this.getFavoritedPokemons()
    this.pokemons.forEach(pokemon => {
      pokemon.is_favorite = faves.find(fave => fave.pokemon_id == pokemon.id)?.favorite || 0
    });
  },
  getPokemonIdByUrl(value) {
    return value.split('/')[6]
  },
  getPokemonImgById(id) {
    return `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${id}.png`
  },
  getPokemonArtWorkById(id) {
    return `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${id}.png`
  },
  async getPokemonDetail(id) {
    const res = await axios.get(`https://pokeapi.co/api/v2/pokemon/${id}`)
    return res.data;
  },
  renderFeatherIcons() {
    setTimeout(() => { feather.replace(); }, 50);
  },
  resetPokemonDetail() {
    this.pokemonDetail = {
      height: '',
      id: '',
      moves: [],
      name: '',
      src: '',
      stats: [],
      types: [],
      weight: '',
    }
  },
  async openPokemonDetail(pokemon) {
    if( event.target.closest('.favorite') != null ) return
    this.isLoading = true
    $('#pokemonDetailModal').modal({ keyboard: false, backdrop: 'static' })

    try {
      const pokemonDetail = await this.getPokemonDetail(pokemon.id)
      
      this.pokemonDetail = {
        height: pokemonDetail.height,
        id: pokemonDetail.id,
        moves: pokemonDetail.moves,
        name: pokemonDetail.name,
        src: pokemon.src,
        stats: pokemonDetail.stats,
        types: pokemonDetail.types,
        weight: pokemonDetail.weight,
      }

      setTimeout(() => { this.isLoading = false }, 50);
    } catch (error) {
      console.log(error.response.data.error)
      return
    }
  },
  closePokemonDetail() {
    this.isLoading = false
    this.resetPokemonDetail()
  },
  getMaxStat(stat_name, base_stat) {
    const max_iv = 31
    const max_ev = 63
    const nature = 1.1

    if (stat_name == 'hp') {
      return Math.floor(base_stat * 2 + (110 + max_iv + max_ev))
    } else {
      return Math.floor((base_stat * 2 + (5 + max_iv + max_ev) ) * nature)
    }
  },
  prevPage() {
    if(this.prevPageLink) this.getPokemons(this.prevPageLink)
  },
  nextPage() {
    if(this.nextPageLink) this.getPokemons(this.nextPageLink)
  },
},
filters: {
  decimetersToFeetAndInch (decimeter) {
    const centimeter = decimeter * 10
    let inches = (centimeter * 0.393700787).toFixed(0)
    const feet = Math.floor(inches / 12)
    inches %= 12;
    return `${feet}' ${inches}"`
  },
  decimetersToCentimeters(decimeter) {
    const product = decimeter * 10
    return `${product} cm`
  },
  hectogramsToPounds(hectograms) {
    const quotient = hectograms / 4.536
    return `${quotient.toFixed(1)} lbs`;
  },
  hectogramsToKilograms(hectograms) {
    const quotient = hectograms / 10
    return `${quotient.toFixed(1)} kg`;
  },
  prettifyStatName(stat) {
    if ( stat == 'hp') {
      return 'HP'
    } else if (stat == 'special-attack') {
      return 'sp. atk'
    } else if (stat == 'special-defense') {
      return 'sp. def'
    } else {
      return stat
    }
  }
}
}
</script>

<style lang="scss" scoped>
.force-center {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  z-index: 1;
  background: #2E3454;
  border-radius: 50%;
}
</style>