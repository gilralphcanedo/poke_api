<template>
  <div class="container">
    <h1 class="text-white font-weight-bold">My Profile</h1>

    <div class="row mt-5">
      <div class="col-lg-4 mb-5">
        <h5 class="mb-4">Basic Information</h5>
        <form @submit.prevent="submitProfile">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" v-model="user.name">
          </div>
          <div class="form-group">
            <label>Birthday</label>
            <date-picker v-model="user.birthdate" :disabled-date="disabledDateForBirthdate" type="date" :default-value="defaultDateForBirthdate"></date-picker>
          </div>
          <div class="text-right">
            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
          </div>
        </form>
      </div>
      <div class="offset-lg-2 col-lg-6">
        <div class="mb-3">
          <h5>Pokemons you like</h5>
          <div class="d-flex align-items-center justify-content-center h-min-150" v-if="!user.likes.length">
            <p class="mb-0 px-5 py-2 bg-white">Nothing to display here yet</p>
          </div>
          <div class="d-inline-block" v-else>
            <img class="img-fluid" width="150" :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${liked.pokemon_id}.png`" v-for="(liked, likedIndex) in user.likes" :key="likedIndex">
          </div>
        </div>
        <div>
          <h5>Pokemons you hate</h5>
          <div class="d-flex align-items-center justify-content-center h-min-150" v-if="!user.hates.length">
            <p class="mb-0 px-5 py-2 bg-white">Nothing to display here yet</p>
          </div>
          <div class="d-flex align-items-center justify-content-center">
            <img class="img-fluid" width="150" :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${hated.pokemon_id}.png`" v-for="(hated, hatedIndex) in user.hates" :key="hatedIndex">
          </div>
        </div>
      </div>
    </div>

    <div>
      <h5 class="my-4">Your favorite pokemons</h5>
      <div class="d-flex align-items-center h-min-150" v-if="!user.favorites.length">
        <p class="mb-0 px-5 py-2 bg-white max-w-content">Nothing to display here yet</p>
      </div>
      <div class="d-flex align-items-center h-min-150" v-else>
        <img class="img-fluid" width="150" :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${favorited.pokemon_id}.png`" v-for="(favorited, favoritedIndex) in user.favorites" :key="favoritedIndex">
      </div>
    </div>

  </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import swal from 'sweetalert';
import 'vue2-datepicker/index.css';

export default {
  components: { DatePicker, swal },
  data() {
    return {
      user: {}
    }
  },
  computed: {
    defaultDateForBirthdate() {
      const age_restriction = 13
      return moment().subtract(age_restriction, 'years').format('YYYY-MM-DD')
    },
  },
  created() {
    this.getUserProfile()
  },
  methods: {
    async getUserProfile() {
      const res = await axios.get('user-profile')
      res.data.birthdate = new Date(res.data.birthdate)
      this.user = res.data
    },
    async submitProfile() {
      try {
        const birthdate = this.user.birthdate ? moment(this.user.birthdate).format('YYYY-MM-DD HH:mm:ss') : ''
        const res = await axios.post('update-profile', { name: this.user.name, birthdate: birthdate })
        swal({title: "Success!", text: "You have updated your information", icon: "success", closeOnClickOutside: false })
      } catch (error) {

        const errors = Object.values(error.response.data.errors)

        if(errors.length) {
          let html = ''
          errors.forEach(error => html += `<li>${error}</li>`)
          
          let wrapper = document.createElement('div');
          wrapper.innerHTML = `<ul class="pl-0 mb-0" style="list-style: none;">${html}</ul>`
          
          swal({ title: "Oops!", content: wrapper, icon: "error", closeOnClickOutside: false })
        } else {
          swal({title: "Oops!", text: "Something went wrong on your request", icon: "error", closeOnClickOutside: false })
        }
        
      }
    },
    disabledDateForBirthdate(date) {
      const age_restriction = 13
      return date > moment().subtract(age_restriction, 'years')
    },
  }
}
</script>

<style lang="scss">
  .mx-datepicker {
    display: block;
    width: 100%;
    .mx-input {
      height: 37px;
    }
  }
</style>

<style lang="scss" scoped>
  h5,
  form {
    color: #ffffff;
  }
  .h-min-150 {
    min-height: 150px;
  }
  .max-w-content {
    max-width: fit-content;
  }
</style>