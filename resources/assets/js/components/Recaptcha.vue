<template>
    <div id="g-recaptcha" class="g-recaptcha" :data-sitekey="sitekey"></div>
</template>

<script>
    export default {
        props: [ 'sitekey' ],

        mounted () {
            if (window.grecaptcha) {
                this.widgetId = window.grecaptcha.render('g-recaptcha', {
                    sitekey: this.sitekey
                })
            }
        },

        data () {
            return {
                widgetId: 0
            }
        }
    }
</script>

