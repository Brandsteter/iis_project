<template>
    <div>
        <input v-model="fields.email" type="email">
        <input v-model="fields.password" type="password">
        <button @click="submit">Submit</button>
    </div>
</template>

<script>
export default {
    data(){
        return {
            fields: {
                email: "",
                password: ""
            }
        }
    },

    methods: {
        submit(){
            axios.post('/auth/login', this.fields).then((response)=>{
                if (response)
                {
                    window.location.href = '/'
                }
            })
        }
    }
}
</script>
