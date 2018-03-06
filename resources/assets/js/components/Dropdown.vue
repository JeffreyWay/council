<template>
    <div @mouseover="activate"
         @mouseout="deactivate"
         class="relative cursor-pointer relative z-10"
    >
        <slot name="heading"></slot>

        <div v-show="active"
             class="bg-grey-light p-6 text-black absolute rounded pin-r pin-t w-48"
             style="border-top-right-radius: 28px 22px; top: 7px; right: 4px;"
        >
            <h4 class="mb-4">My Links</h4>

            <ul class="list-reset">
                <slot name="links"></slot>
            </ul>
        </div>
    </div>
</template>

<script>

    import activation from '../mixins/activation';

    export default {
        mixins: [ activation ],
    }
</script>
