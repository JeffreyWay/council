<template>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
           aria-expanded="false">Channels <span class="caret"></span></a>

        <div class="dropdown-menu" >
            <div style="padding:.5rem 1rem">
                <input type="text" class="form-control" v-model="filter" placeholder="Filter Channels..."/>
            </div>
            <ul style="max-height: 400px; overflow:auto; ">
                <li v-for="channel in filteredThreads"><a :href="'/threads/' + channel.slug" v-text="channel.name"></a>
                </li>
            </ul>
        </div>
    </li>
</template>

<script>
    export default {
        props: ['channels'],
        data() {
            return {
                filter: ''
            }
        },
        computed: {
            filteredThreads() {
                return this.channels.filter(channel => {
                    return channel.name.toLowerCase().includes(this.filter.toLocaleLowerCase())
                })
            }
        }
    }
</script>
