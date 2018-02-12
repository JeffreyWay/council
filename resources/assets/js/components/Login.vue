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
                    this.feedback = error.response.data;
                    this.loading = false;
                });
        }
    }
};
</script>
