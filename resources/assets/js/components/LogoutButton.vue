<template>
    <a :href="route" @click.prevent="onClick">
        <slot/>
    </a>
</template>

<script>
    export default {
        props: {
            route: {
                type: String,
                default: "/logout"
            }
        },

        methods: {
            onClick() {
                axios.post(this.route).then(response => {
                    window.location.href = response.request.responseURL
                });
            }
        }
    };
</script>
