<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const message = computed(() => page.props.message as any);
type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), { 
    breadcrumbs: () => [],
});
</script>
 
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <slot name="header-actions"/>
        </template>
        <div v-if="message?.success" class="m-4 p-4 bg-green-100 rounded-lg shadow-xl shadow-gray-200/50 text-green-900 flex items-center">
            <span>{{ message.success }}</span>
            <button class="ml-auto cursor-pointer" @click="message.success = null">X</button>
        </div>
        <div v-if="message?.error" class="m-4 p-4 bg-red-100 rounded-lg shadow-xl shadow-gray-200/50 text-red-900 flex items-center">
            <span>{{ message.error }}</span>
            <button class="ml-auto cursor-pointer" @click="message.error = null">X</button>
        </div>
        <slot />
    </AppLayout>
</template> 
