<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import route from '@/routes/accounts';
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const { formatMoney } = useCurrency();
interface Account {
    id: number;
    title: string;
    current_balance: number;
}
const props = defineProps<{
    account: Account;
    single_account: Boolean;
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: route.index(),
    },
    {
        title: props.account.title,
        href: route.show(props.account.id),
    },
];
const statusModalDelete = ref(false);
const deleting = ref(false);
const form = useForm();
const deleteAccount = () => {
    deleting.value = true;
    form.delete(route.destroy({account:props.account.id}).url, {
        onFinish: () => {
            deleting.value = false;
        }
    });
}
</script>

<template>
    <Head :title="`Account - ${account.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <template #header-actions>
            <Link :href="route.index().url" class="button button-light">
                Return
            </Link>
             <Link :href="route.edit(account.id).url" class="button button-dark">
                Edit account
             </Link>
        </template>

        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div>
                <h6 class="mb-2">Title</h6>
                <p class="font-semibold">{{ account.title }}</p>
            </div>
            <div>
                <h4 class="mb-2">Current balance</h4>
                <p class="font-semibold">{{ formatMoney(account.current_balance) }}</p>
            </div>
            <div class="flex mt-auto justify-end">
                <button class="link link-danger" @click="statusModalDelete = true" v-if="!single_account">Archive account</button>
            </div>

            <div v-if="statusModalDelete" class="modal">
                <div class="modal-box">
                    <div class="modal-header"> 
                        Are you sure you want to archive this account? 
                    </div>
                    <div class="modal-body">
                        All its transactions will be archived too
                    </div>
                    <div class="modal-footer">
                        <button :class="['button button-light',  deleting  ? ' button-disabled' : '']" @click="statusModalDelete = false">Cancel</button>
                        <button @click="deleteAccount" :class="['button button-danger',  deleting  ? ' button-disabled' : '']">I'm sure</button>
                    </div>
                </div>
            </div> 
        </div>
    </AppLayout>
</template>
