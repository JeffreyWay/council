<template>
    <div class="flex items-center">
        <div class="title"/>
        <div class="mt-0 mr-0 w-full pl-6" :class="{'mb-10': !last }">
            <div class="rounded border border-grey">
                <div class="px-6 py-4">
                    <div class="text-sm mb-1">
                        <div class="text-grey-darkest flex content-center">
                            <slot name="activity"></slot>
                        </div>
                    </div>
                    <slot name="heading"></slot>
                    <slot name="body"></slot>
                </div>
                <slot name="badges"></slot>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            last: {
                type: Boolean,
                required: false,
                default: false
            }
        }
    };
</script>
