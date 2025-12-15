<template>
  <footer class="p-4 text-center text-sm text-gray-500 bg-gray-100">
    Visitas de esta página: {{ pageVisits }}
  </footer>
</template>

<script>
export default {
  computed: {
    pageVisits() {
      return this.$page.props.pageVisits || 0;
    }
  }
}
</script>
