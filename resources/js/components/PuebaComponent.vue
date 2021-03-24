<template>
    <div>
        {{count}}
    </div>
</template>

<script>
import store from '../store/index';

const componentA = {
    computed: {
        count() {
            return this.$store.state.count;
        }
    }
};

export default {
    
}
</script>

