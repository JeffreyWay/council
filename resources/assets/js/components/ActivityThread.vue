<template>
    <activity-layout>
        <span slot="activity">
            added a&nbsp;<a class="mr-1 text-blue" :href="activity.subject.path"><strong>thread</strong></a>
            {{ humanTime(activity.subject.created_at) }} in:
        </span>

        <div slot="heading" class="text-xl font-semibold my-4">
            <a class="text-blue font-bold mb-4" :href="activity.subject.path">"{{ activity.subject.title }}"</a>

            <p class="text-2xs text-grey-darkest font-medium mb-4">
                Posted By:
                <a :href="activity.subject.creator.username" class="text-blue">
                    {{ activity.subject.creator.username }}
                </a>
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
            <!-- Replies Count Badge -->
            <div v-if="activity.subject.replies_count > 0" class="text-grey-darker text-2xs font-semibold flex items-center mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="14" viewBox="0 0 19 14" class="mr-1">
                    <g fill="none" fill-rule="evenodd">
                        <path d="M0-3h19v19H0z"/>
                        <path fill="#C1C1CE" d="M10.292 6.5h5.541v1.187h-5.541V6.5zm0-1.98h5.541v1.188h-5.541V4.521zm0 3.96h5.541v1.187h-5.541V8.479zM16.625.166H2.375C1.505.167.792.879.792 1.75v10.292c0 .87.712 1.583 1.583 1.583h14.25c.87 0 1.583-.713 1.583-1.583V1.75c0-.87-.712-1.583-1.583-1.583zm0 11.875H9.5V1.75h7.125v10.292z"/>
                    </g>
                </svg>

                {{ activity.subject.replies_count }} Reply(s)
            </div>
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
