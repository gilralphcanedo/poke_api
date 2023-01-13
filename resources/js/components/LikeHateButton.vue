<template>
  <div class="like-hate-wrapper">
    <div :class="['like', actions[0].active ? 'is-liked text-success' : '']" @click="toggleAction(actions[0].action)">
      <i data-feather="thumbs-up" width="18" height="18"></i>
      <span>{{actions[0].active  ? 'Liked' : 'Like'}}</span>
    </div>
    <div class="vr"></div>
    <div :class="['hate', actions[1].active ? 'is-hated text-danger' : '']" @click="toggleAction(actions[1].action)">
      <i data-feather="thumbs-down" width="18" height="18"></i>
      <span>{{actions[1].active  ? 'Hated' : 'Hate'}}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['pokemon_id'],
  data() {
    return {
      actions: [
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
          active: 0,
        }
      ],
    }
  },
  created() {
  },
  watch: {
    pokemon_id(oldVal, newVal) {
      if(oldVal) {
        this.setPokemonActionStatus()

      } else {
        this.resetActionsArr()
      }
    }
  },
  methods: {
    resetActionsArr() {
      this.actions = [
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
          active: 0,
        }
      ]
    },
    async setCurrentAction(actions, pokemon_id) {
      const res = await axios.post('actions/set', { actions, pokemon_id })
    },
    async setPokemonActionStatus() {
      const payload = { pokemon_id: this.pokemon_id};
      const params = new URLSearchParams(payload);
      const res = await axios.get(`action-status?${params}`)
      const {like, hate, favorite} = res.data

      this.actions[0].active = like || 0
      this.actions[1].active = hate || 0
      this.actions[2].active = favorite || 0
    },
    async getPokemonsLiked() {
      const res = await axios.get('likes')
      this.pokemonsLiked = res.data
    },
    async getPokemonsHated() {
      const res = await axios.get('hates')
      this.pokemonsHated = res.data
    },
    async toggleAction(action) {
      const limit = 3
      let count = 0
      let condition = false
      if(action == 'like') {
        await this.getPokemonsLiked()
        condition = this.actions[0].active == 0
        count = this.pokemonsLiked.length
      } else if(action == 'hate') {
        await this.getPokemonsHated()
        condition = this.actions[1].active == 0
        count = this.pokemonsHated.length
      }
      
      if(count >= 3 && condition) {
        swal("Oops!",`You have reached the maximum limit (3) of ${action}s`, "error")
        return
      }

      const el = event.target.parentElement
      let thisAction = this.actions.find(obj => obj.action == action)
      const active = !thisAction.active ? 1 : 0
      this.actions.forEach((obj) => obj.active = 0);
      thisAction.active = active
      
      this.setCurrentAction(this.actions, this.pokemon_id)
    },
  }
}
</script>

<style lang="scss" scoped>
.like-hate-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-inline: auto;
  margin-bottom: 1.5rem;
  max-width: fit-content;
  padding: 0.5rem 1rem;
  background-color: #ffffff;
  color: initial;
  border-radius: 2rem;
  svg {
    margin-right: 0.5rem;
  }
  .like, .hate {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(0,0,0,0.80);
    font-weight: 500;
    min-width: 70px;
  }
  .vr {
    margin-inline: 0.75rem;
    height: 1rem;
    width: 1px;
    background-color: rgba(0,0,0,0.5);
  }
}
</style>