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
    @import '~tributejs/dist/tribute.css';
</style>

<script>
    import Trix from 'trix';
    import Tribute from 'tributejs';

    export default {
        props: ['name', 'value', 'placeholder'],

        data() {
            return {
                query: ''
            }
        },

        methods: {
            change({target}) {
                this.$emit('input', target.value)
            },
            remoteSearch(text, callback) {
                this.query = text;

                axios.get(`/api/users?name=${text}`)
                    .then(function({data}) {
                        callback(data);
                    }).catch(() => {
                        callback([]);
                })
            }
        },

        mounted() {
            let $vm = this;
            let el = $vm.$refs.trix;

            let tribute = new Tribute({
                values: function(text, cb){
                    $vm.remoteSearch(text, cb);
                },
                lookup: 'name',
            }).attach(el);

            el.addEventListener('tribute-replaced', function (e) {
                // set selected range
                let range = el.editor.getSelectedRange();
                el.editor.setSelectedRange([range[0] - $vm.query.length, range[1]]);
                // // delete typed text and insert the matched item
                el.editor.deleteInDirection("forward");
                el.editor.insertString(e.detail.item.original.name);
            });

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
