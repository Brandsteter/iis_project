<template>
    <div>
        <input v-model="fields.name" type="text">
        <input v-model="fields.email" type="email">
        <input v-model="fields.password" type="password">
        <input v-model="fields.passwordRepeat" type="password">
        <button @click="submit">Submit</button>
    </div>
</template>

<script>
export default {
    data(){
        return {
            fields: {
                name: "",
                email: "",
                password: "",
                passwordRepeat: ""
            }
        }
    },

    methods: {
        submit(){
            axios.post('/auth/register', this.fields)
        }
    }
}
</script>
