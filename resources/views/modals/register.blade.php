<register inline-template>
    <modal name="register" height="auto">
        <form class="p-10" @submit.prevent="register">
            <div class="mb-6">
                <label for="name" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Name</label>
                <input type="text" class="w-full p-2 leading-normal" id="name" name="name" autocomplete="name" placeholder="John Doe" value="{{ old('name') }}" required v-model="form.name" @keydown="errors.name = false">
                <span v-if="errors.name" v-text="errors.name[0]" class="text-xs text-red"></span>
            </div>

            <div class="mb-6">
                <label for="username" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Username</label>
                <input type="text" class="w-full p-2 leading-normal" id="username" name="username" autocomplete="username" placeholder="johndoe" value="{{ old('username') }}" required v-model="form.username" @keydown="errors.username = false">
                <span v-if="errors.username" v-text="errors.username[0]" class="text-xs text-red"></span>
            </div>

            <div class="mb-6">
                <label for="email" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Email</label>
                <input type="text" class="w-full p-2 leading-normal" id="email" name="email" autocomplete="email" placeholder="joe@example.com" value="{{ old('email') }}" required v-model="form.email" @keydown="errors.email = false">
                <div v-if="errors.email" v-text="errors.email[0]" class="text-xs text-red mt-2"></div>
            </div>

            <div class="mb-6">
                <label for="password" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Password</label>
                <input type="password" class="w-full p-2 leading-normal" id="password" name="password" autocomplete="new-password" required v-model="form.password" @keydown="errors.password = false">
                <div v-if="errors.password" v-text="errors.password[0]" class="text-xs text-red mt-2"></div>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Confirm Password</label>
                <input type="password" class="w-full p-2 leading-normal" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required v-model="form.password_confirmation" @keydown="errors.password = false">
            </div>

            <div class="flex items-center -mx-4">
                <button type="submit" class="btn is-green flex-1 mx-4" :class="loading ? 'loader' : ''" :disabled="loading">Register</button>
            </div>

            <div class="mt-6" v-if="feedback">
                <div class="text-xs text-red mt-2" v-text="feedback"></div>
            </div>
        </form>
    </modal>
</register>
