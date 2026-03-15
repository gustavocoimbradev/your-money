<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import route from '@/routes/accounts';
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: route.index(),
    },
    {
        title: 'New account',
        href: route.create(),
    }
];
const props = defineProps({
    accounts: Array
});
const form = useForm<{
    title: string;
}>({
    title: ''
});
const creating = ref(false);
const submit = () => {
    creating.value = true;
    form.post(route.store().url, {
        onSuccess: () => form.reset(),
        onFinish: () => creating.value = false
    });
}
</script>

<template>
    <Head title="New account" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <template #header-actions>
            <Link :href="route.index().url" :class="['button button-light', creating ? ' button-disabled' : '']">
                Cancel
            </Link>
             <button type="button" @click="submit" :class="['button button-dark', creating ? ' button-disabled' : '']">
                Confirm
            </button>
        </template>

        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <form class="flex flex-col gap-3" @submit.prevent="submit">
                <fieldset class="form-fieldset">
                    <label for="title" class="form-label">Title</label>
                    <input :class="['form-input', $page.props.errors.title ? 'has-errors' : '']" v-model="form.title"/>
                    <span class="form-error" v-if="$page.props.errors.title">{{ $page.props.errors.title }}</span>
                </fieldset>
            </form>
        </div>
    </AppLayout>
</template>
