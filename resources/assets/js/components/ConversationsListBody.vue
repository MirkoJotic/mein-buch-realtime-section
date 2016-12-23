<template>
    <div class="sidebar-body">
        <div class="contacts-search">
            <input type="text" class="searchinput" placeholder="Search Members">
            <i class="searchicon icon ion-ios-search"></i>
        </div>
        <ul class="contacts-list" style="overflow: hidden; width: auto; height: 482px;">
            <div v-for="thread in threads">
                <conversations-list-body-item :thread="thread"></conversations-list-body-item>
            </div>
        </ul>
    </div>
</template>

<script>
export default {
  mounted() {
    console.log('conversation list ready')
    console.log(this.threads)
  },
  methods: {
  },
  computed: {
    threads: function() {
      return this.$store.getters.conversations
    }
  }
}



</script>
