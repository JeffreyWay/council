import moment from "moment";

export default {
    props: {
        activity: {
            type: Object,
            required: true
        },
        last: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    methods: {
        humanTime(timestamp) {
            return moment(timestamp).fromNow();
        }
    }
};
