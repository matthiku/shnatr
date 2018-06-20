<template>
  <div class="card">
    <div v-if="title" class="card-header">
      {{ title }}
    </div>

    <div class="card-body p-0 p-sm-1 p-md-2">
      <slot/>
    </div>
  </div>
</template>

<style scoped>
</style>


<script>
export default {
  name: 'Card',

  props: {
    title: { type: String, default: null }
  }
}
</script>
