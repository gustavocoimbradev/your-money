<script setup lang="ts">
import { ref } from 'vue'; 
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import route from '@/routes/transactions';
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const { formatMoney, maskMoney } = useCurrency();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transactions',
        href: route.index(),
    },
    {
        title: 'New transaction',
        href: route.create(),
    }
];
interface Account {
    id: number;
    title: string;
    balance: number;
}
const props = defineProps<{
    accounts: Account[];
}>();
const creating = ref(false);
const handleValue = (event: Event) => {
    form.value = maskMoney(event);
}
const form = useForm({
    title: '',
    description: '',
    value: formatMoney(0.00),
    account_id: props.accounts[0].id,
    type: 1,
    reference: new Date().toISOString().split('T')[0]
});
const submit = () => {
    creating.value = true;
    form.transform((data) => {
        let cleanValue = data.value.toString().replace(/[^\d.]/g, '');
        let numericValue = Number(cleanValue);
        return {
            ...data,
            value: data.type == 1 ? numericValue : numericValue * -1
        };
    })
    .post(route.store().url, {
        onFinish: () => creating.value = false
    });
}
</script>

<template>
    <Head :title="`New transaction`" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <template #header-actions>
            <Link :href="route.index().url" :class="['button button-light', creating ? ' button-disabled' : '']">
                Cancel
            </Link>
             <button type="button" @click="submit" :class="['button button-dark', creating ? ' button-disabled': '']">
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
                <fieldset class="form-fieldset">
                    <label for="value" class="form-label">Value</label>
                    <input :class="['form-input', $page.props.errors.value ? 'has-errors' : '']" :value="form.value" @input="handleValue"/>
                    <span class="form-error" v-if="$page.props.errors.value">{{ $page.props.errors.value }}</span>
                </fieldset>
                <fieldset class="form-fieldset">
                    <label for="type" class="form-label">Type</label>
                    <select v-model="form.type" class="form-input">
                        <option value="1">Income</option>
                        <option value="2">Expense</option>
                    </select> 
                </fieldset>
                <fieldset class="form-fieldset">
                    <label for="account_id" class="form-label">Account</label>
                    <select :class="['form-input', $page.props.errors.account_id ? 'has-errors' : '']" v-model="form.account_id">
                        <option v-for="account in accounts" :value="account.id">{{ account.title }}</option>
                    </select> 
                    <span class="form-error" v-if="$page.props.errors.account_id">{{ $page.props.errors.account_id }}</span>
                </fieldset>
                <fieldset class="form-fieldset">
                    <label for="reference" class="form-label">Reference</label>
                    <input type="date" :class="['form-input', $page.props.errors.reference ? 'has-errors' : '']" v-model="form.reference"/>
                    <span class="form-error" v-if="$page.props.errors.reference">{{ $page.props.errors.reference }}</span>
                </fieldset>
            </form>
        </div>
    </AppLayout>
</template>
