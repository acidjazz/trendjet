<template lang="pug">
nav.pagination(
  role="navigation",
  arial-label="navigation",
  v-if="paginate.pages && paginate.pages.length > 1")

  a.pagination-previous.ani-slide-in-left(
    :disabled="paginate.current_page === 1",
    @click="query({page: paginate.current_page-1})") Previous

  a.pagination-next.ani-slide-in-left.delay-1(
    :disabled="paginate.current_page === paginate.last_page"
    @click="query({page: paginate.current_page+1})") Next

  ul.pagination-list
    li(v-for="page, index in paginate.pages")
      a.pagination-link.ani-slide-in-right(
        :class="[{'is-current': page === paginate.current_page}, `delay-${index+1}`]",
        @click="query({page: page})",
        :aria-label="`Go to page ${page}`") {{ page }}
</template>

<script>
export default {
  props: {
    paginate: {
      type: Object,
      required: true,
    },
    query: {
      type: Function,
      required: true,
    },
  },
}
</script>
