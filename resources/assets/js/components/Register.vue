<script>
export default {
    data() {
        return {
            form: {
                name: "",
                username: "",
                email: "",
                password: "",
                password_confirmation: ""
            },
            feedback: "",
            loading: false,
            errors: {}
        };
    },

    methods: {
        register() {
            this.loading = true;

            axios
                .post("/register", this.form)
                .then(response => {
                    location.reload();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;

                    this.loading = false;
                });
        }
    }
};
</script>
