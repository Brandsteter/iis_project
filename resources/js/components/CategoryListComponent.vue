<template>
    <div>
        <hr>
        <ul class="nested">
            <li v-for="(category, index) in children">
                <a :href="`/category/${category.id}/detail`">{{ category.name }}</a>
                <categoryList :categoryId="category.id"></categoryList>
            </li>
        </ul>

    </div>
</template>

<script>

export default {
    props: ['categoryId'],
    data() {
        return {
            children: [],
        };
    },
    mounted() {
        this.getChildCategories(this.categoryId);
    },
    methods: {
        getChildCategories(categoryId) {
            const url = `/category/${categoryId}`;
            axios.get(url)
                .then(response => {
                    // this.parentName = category.name;
                    this.children = response.data;
                })
                .catch(error => {
                    console.error('Error fetching unapproved categories:', error);
                });
        },
    }
};
</script>
