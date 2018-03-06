export default {

        data() {
            return {
                active: false,
                timer: null, 
            };
        },

        methods: {
            activate() {

                window.clearTimeout( this.timer );

                this.timer = window.setTimeout( () => {
                    this.active = true;
                }, 100 );

            },

            deactivate() {

                window.clearTimeout( this.timer );

                this.timer = window.setTimeout( () => {
                    this.active = false;
                }, 100 );

            }
        }

}
