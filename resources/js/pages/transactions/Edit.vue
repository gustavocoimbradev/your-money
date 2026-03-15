<script setup lang="ts">
import { ref } from 'vue'; 
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import route from '@/routes/transactions';
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const { formatMoney, maskMoney } = useCurrency();

interface Account {
    id: number;
    title: string;
    balance: number;
}
interface Transaction {
    id: number;
    title: string;
    account_id: number;
    value: number;
    reference: string;
}
const props = defineProps<{
    transaction: Transaction;
    accounts: Account[];
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transactions',
        href: route.index(),
    },
    {
        title: props.transaction.title,
        href: route.edit(props.transaction.id)
    },
    {
        title: 'Edit transaction',
        href: route.edit(props.transaction.id)
    }
];
const editing = ref(false);
const handleValue = (event: Event) => {
    form.value = maskMoney(event);
}
const form = useForm({
    title: props.transaction?.title??'',
    value: formatMoney(Math.abs(props.transaction?.value)??0.00),
    account_id: props.transaction?.account_id??0,
    type: props.transaction?.value > 0 ? 1 : 2,
    reference: props.transaction?.reference
});
const submit = () => {
    if (!props.transaction?.id) return;
    editing.value = true;
    form.transform((data) => {
        let cleanValue = data.value.toString().replace(/[^\d.]/g, '');
        let numericValue = Number(cleanValue);
        return {
            ...data,
            value: data.type == 1 ? numericValue : numericValue * -1
        };
    })
    .put(route.update({ transaction: props.transaction.id }).url, {
        onFinish: () => editing.value = false
    });
}
const statusModalDelete = ref(false); 
const deleting = ref(false);
const formDelete = useForm();
const deleteTransaction = () => {
    formDelete.delete(route.forceDestroy({transaction: props.transaction.id}).url);
}
</script>

<template>
    <Head :title="`Edit transaction - ${transaction.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <template #header-actions>
            <Link :href="route.index().url" :class="['button button-light', editing ? ' button-disabled' : '']">
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
                <fieldset class="form-fieldset">
                    <label for="Value" class="form-label">Value</label>
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

            <div class="flex mt-auto justify-end">
                <button class="link link-danger" @click="statusModalDelete = true">Delete transaction</button>
            </div>

        </div>

        <div v-if="statusModalDelete" class="modal">
            <div class="modal-box">
                <div class="modal-header">
                    Are you sure you want to delete this transaction? 
                </div>
                <div class="modal-body">
                    This action is irreversible. You won't be able to restore this transaction
                </div>
                <div class="modal-footer">
                    <button :class="['button button-light',  deleting  ? ' button-disabled' : '']" @click="statusModalDelete = false">Cancel</button>
                    <button @click="deleteTransaction" :class="['button button-danger',  deleting  ? ' button-disabled' : '']">I'm sure</button>
                </div>
            </div>
        </div>
        
    </AppLayout>
</template>
