<login inline-template>
    <modal name="login" height="auto">
        <form class="p-10" @submit.prevent="login" @keydown="feedback = ''">
            <div class="mb-6">
                <label for="email" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Email</label>
                <input type="text" class="w-full p-2 leading-normal" id="email" name="email" autocomplete="email" placeholder="joe@example.com" value="{{ old('email') }}" required v-model="form.email">
            </div>

            <div class="mb-6">
                <label for="password" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Password</label>
                <input type="password" class="w-full p-2 leading-normal" id="password" name="password" autocomplete="current-password" required v-model="form.password">
            </div>

            <div class="flex -mx-4">
                <button type="button" class="btn flex-1 mx-4" @click="$modal.hide('login')">Cancel</button>
                <button type="submit" class="btn is-green flex-1 mx-4" :class="loading ? 'loader' : ''" :disabled="loading">Log In</button>
            </div>

            <div class="mt-6" v-if="feedback">
                <span class="text-xs text-red" v-text="feedback"></span>
            </div>
        </form>
    </modal>
</login>
