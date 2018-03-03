<template>
    <div class="flex items-center">
        <div class="title">&nbsp;</div>
        <div class="mt-0 mr-0 mb-10 w-full pl-6">
            <div class="rounded border border-grey-light">
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
