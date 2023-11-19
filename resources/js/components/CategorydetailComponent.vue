<template>
    <div>
        <h1>Category Details</h1>
        <h2>{{category.name}}</h2>
</template>

<script>
import {isRole, getAuthUser} from "../app";
export default {
    props: {
        category: {
            required: true,
            type: Object,
        }
    },
    data() {
       return {
           userId: null,
       }
    },
    methods: {
      isRole,
      getAuthUser
    },
    created() {
    }

}
</script>





<style>
</style>
