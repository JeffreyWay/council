<template>
    <a href="#" @click.prevent="toggle" class="inline-block text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" class="fill-current mb-1" :class="active ? 'text-green' : 'text-grey-light'">
            <g fill="none" fill-rule="evenodd">
                <path d="M-2-3h24v24H-2z"/>
                <path :fill="active ? '#3490dc' : '#dae1e7'" d="M14.5 0c-1.74 0-3.41.81-4.5 2.09C8.91.81 7.24 0 5.5 0 2.42 0 0 2.42 0 5.5c0 3.78 3.4 6.86 8.55 11.54L10 18.35l1.45-1.32C16.6 12.36 20 9.28 20 5.5 20 2.42 17.58 0 14.5 0z"/>
            </g>
        </svg>

        <span v-text="count" class="block" :class="active ? 'text-blue' : 'text-grey-light'"></span>
    </a>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                active: this.reply.isFavorited
            }
        },

        computed: {
            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        },

        methods: {
            toggle() {
                this.active ? this.destroy() : this.create();
            },

            create() {
                axios.post(this.endpoint);

                this.active = true;
                this.count++;
            },

            destroy() {
                axios.delete(this.endpoint);

                this.active = false;
                this.count--;
            }
        }
    }
</script>
