<template>
  <div class="main-layout">
    <navbar/>

    <div class="container mt-1 mt-sm-2 mt-md-3 mt-lg-4 px-0 px-sm-1 px-md-2">
      <child/>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'MainLayout',

  components: {
    Navbar
  }
}
</script>
