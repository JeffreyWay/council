<template>
    <a href="#" class="ml-2 pl-2 border-l" :class="isActive ? 'font-bold': ''" @click.prevent="subscribe" v-text="isActive ? 'Subscribed' : 'Subscribe'"></a>
</template>

<script>
export default {
    props: ["active"],

    data() {
        return {
            isActive: this.active
        };
    },

    methods: {
        subscribe() {
            axios[this.isActive ? "delete" : "post"](
                location.pathname + "/subscriptions"
            );

            this.isActive = !this.isActive;

            if (this.isActive) {
                flash("Okay, we'll notify you when this thread is updated!");
            }
        }
    }
};
</script>
