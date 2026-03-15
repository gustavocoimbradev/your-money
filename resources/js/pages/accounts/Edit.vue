<script setup lang="ts">
import { ref } from 'vue'; 
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import route from '@/routes/accounts';
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const { formatMoney, maskMoney } = useCurrency();
interface Account {
    id: number;
    title: string;
}
const props = defineProps<{
    account: Account;
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: route.index(),
    },
    {
        title: props.account.title,
        href: route.show(props.account.id)
    },
    {
        title: 'Edit account',
        href: route.edit(props.account.id)
    }
];
const editing = ref(false);

const form = useForm({
    title: props.account?.title??''
});
const submit = () => {
    if (!props.account?.id) return;
    editing.value = true;
    form.put(route.update({ account: props.account?.id }).url, {
        onFinish: () => editing.value = false
    });
}
</script>

<template>
    <Head :title="`Edit account - ${account.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <template #header-actions>
            <Link :href="route.show(account.id).url" :class="['button button-light', editing ? ' button-disabled' : '']">
                Cancel
            </Link>
             <button type="button" @click="submit" :class="['button button-dark', editing ? ' button-disabled': '']">
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
