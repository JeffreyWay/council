<script>
export default {
    data() {
        return {
            form: { email: "", password: "" },
            feedback: "",
            loading: false
        };
    },

    methods: {
        login() {
            this.loading = true;

            axios
                .post("/login", this.form)
                .then(() => location.reload())
                .catch(error => {
                    this.feedback =
                        "The given credentials are incorrect. Please try again.";
                    this.loading = false;
                });
        },

        register() {
            this.$modal.hide("login");
            this.$modal.show("register");
        }
    }
};
</script>
