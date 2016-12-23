<template>
  <div>
    TEST COMPONENT DISPLAY
  </div>
</template>

<script>
 export default {
  mounted() {

  },
  methods: {
    useHttpFromStore: function() {
      this.$store.dispatch('useHttpFromStore');
    }
  }
 }
</script>

<style>

</style>