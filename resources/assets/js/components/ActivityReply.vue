<template>
    <activity-layout>
        <span slot="activity">
            added a&nbsp;<a class="mr-1 text-blue font-bold" :href="activity.subject.path">reply</a>
            {{ humanTime(activity.subject.created_at) }} in:
        </span>

        <div slot="heading" class="text-xl font-semibold my-4">
            <a class="text-blue font-bold mb-4" :href="activity.subject.thread.path">"{{ activity.subject.thread.title }}"</a>

            <p class="text-2xs text-grey-darkest font-medium mb-4">
                Posted By: <a :href="activity.subject.thread.creator.username" class="text-blue">{{
                activity.subject.thread.creator.username }}</a>
            </p>
        </div>

        <div slot="body">
            <div class="text-grey-darkest leading-loose mb-4 max-h-24 overflow-hidden">
                <div class="ml-6 my-4 pl-4 border-l-2 border-grey-dark">
                    <highlight :content="activity.subject.body"/>
                </div>
            </div>

            <div class="flex items-center py-1 text-xs text-grey-darkest">
                &#8943; <a class="ml-1 text-2xs text-blue" :href="activity.subject.path">more</a>
            </div>
        </div>

        <div slot="badges" class="flex items-center mx-6 mb-6 text-sm">
            <!-- Favorites Badge -->
            <div class="text-2xs flex items-center mr-2 text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 20 19" class="fill-current">
                    <g fill="none" fill-rule="evenodd">
                        <path d="M-2-3h24v24H-2z"/>
                        <path fill="#00DF8F" d="M14.5 0c-1.74 0-3.41.81-4.5 2.09C8.91.81 7.24 0 5.5 0 2.42 0 0 2.42 0 5.5c0 3.78 3.4 6.86 8.55 11.54L10 18.35l1.45-1.32C16.6 12.36 20 9.28 20 5.5 20 2.42 17.58 0 14.5 0z"/>
                    </g>
                </svg>

                <span class="text-grey-darker text-2xs font-semibold  ml-1">{{ activity.subject.favoritesCount }} Favorite(s)</span>
            </div>

            <!-- Best Answer Badge -->
            <div v-if="activity.subject.isBest" class="text-grey-darker text-2xs font-semibold flex items-center mr-2">
                <span class="mr-2 font-bold flex items-center">
                    <span class="ml-2 mr-1">Best Answer!</span>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20"
                         class="fill-current text-green">
                        <path fill-rule="evenodd" d="M9.99 0C4.47 0 0 4.48 0 10s4.47 10 9.99 10C15.52 20 20 15.52 20 10S15.52 0 9.99 0zm4.24 16L10 13.45 5.77 16l1.12-4.81-3.73-3.23 4.92-.42L10 3l1.92 4.53 4.92.42-3.73 3.23L14.23 16z"/>
                    </svg>
                </span>
            </div>

            <!-- XP Badge -->
            <div class="flex items-center w-auto h-4 px-2 bg-green rounded font-semibold text-2xs text-white">XP +{{ activity.subject.xp }}</div>
        </div>
    </activity-layout>
</template>

<script>
export default {
    props: {
        activity: {
            required: true
        }
    }
};
</script>
