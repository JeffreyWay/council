<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">

        <trix-editor
                ref="trix"
                input="trix"
                @trix-change="change"
                :placeholder="placeholder">
        </trix-editor>
    </div>
</template>

<style lang="scss">
    @import '~trix/dist/trix.css';
</style>

<script>
    import Trix from 'trix';

    export default {
        props: ['name', 'value', 'placeholder'],

        methods: {
            change({target}) {
                this.$emit('input', target.value)
            }
        },

        watch: {
            value(val) {
                if (val === '') {
                    this.$refs.trix.value = '';
                }
            }
        }
    }
</script>
