<template>
  <div>
      <div v-html="content" ref="content"></div>
  </div>
</template>

<script>
    import Highlighter from 'highlight.js';
    import 'highlight.js/styles/foundation.css';

    export default {
        props: ['content'],

        mounted () {
            this.highlight(this.$refs.content);
        },

        methods: {
            highlight(block) {
                if (! block) return;

                block.querySelectorAll('pre').forEach(
                    node => Highlighter.highlightBlock(node)
                );
            }
        },

        watch: {
            content() {
                this.$nextTick(() => {
                    this.highlight(this.$refs['content']);
                });
            }
        }
    }
</script>
