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
    accounts: Account[],
    archived_accounts: Account[]
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Accounts',
        href: route.index(),
    }
];
const statusModalRestore = ref(false);
const formRestore = useForm({
    account: 0
});
const restoring = ref(false);
const handleModalRestore = (account:number) => {
    formRestore.account = account;
    statusModalRestore.value = true;
}
const restoreAccount = () => {
    restoring.value = true;
    formRestore.patch(route.restore({account: formRestore.account}).url, {
        onSuccess: () => {
            statusModalRestore.value = false;
        },
        onFinish: () => {
            restoring.value = false;
        }
    });
}
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <template #header-actions>
            <Link :href="route.create().url" class="button button-dark">
                New account
            </Link>
        </template>

        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >

            <div class="text-gray-900/70 border-b border-gray-200/50 flex py-3" v-if="accounts.length && archived_accounts.length">
                Active accounts
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-4" v-if="accounts.length">
               
                <Link v-for="account in accounts" :key="account.id" :href="route.show(account.id)" class="shadow-lg shadow-gray-100 p-4 rounded-xl hover:shadow-gray-300/50">
                    <h4 class="text-lg font-medium">{{ account.title }}</h4>
                    <hr class="my-2">
                    <small>Current balance</small>
                    <h6 class="text-md font-medium">
                        <span :class="[account.current_balance < 0 ? 'text-red-600' : 'text-green-600']">
                            {{ formatMoney(account.current_balance) }}
                        </span>
                    </h6>
                </Link>

            </div>

            <div class="text-gray-900/70 border-b border-gray-200/50 flex py-3" v-if="accounts.length && archived_accounts.length">
                Archived accounts
            </div>

            <div class="shadow-lg shadow-gray-100 p-4 rounded-xl px-6 py-4 whitespace-nowrap text-left overflow-x-auto" v-if="archived_accounts.length" >
                <table class="w-full min-w-[500px]">
                    <thead>
                        <tr class="border-b border-gray-200/50 [&>th]:p-2">
                            <th>Title</th>
                            <th>Last balance</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="account in archived_accounts" :key="account.id" class="[&>td]:text-md [&>td]:font-medium [&>td]:text-gray-600 [&>td]:p-2 [&>td]:border-b [&>td]:border-gray-200/50">
                            <td class="w-[40%]">
                                {{ account.title }}
                            </td>
                            <td class="w-[40%]">
                                {{ formatMoney(account.current_balance) }}
                            </td>
                            <td>
                                <button class="button button-light ml-auto w-full text-right" @click="handleModalRestore(account.id)">Restore account</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
  

            <div v-if="statusModalRestore" class="modal">
                <div class="modal-box">
                    <div class="modal-header">
                        Are you sure you want to restore this account? 
                    </div>
                    <div class="modal-body">
                        All its transactions will be restored too
                    </div>
                    <div class="modal-footer">
                        <button :class="['button button-light', restoring ? ' button-disabled' : '']" @click="statusModalRestore = false">Cancel</button>
                        <button @click="restoreAccount" :class="['button button-info', restoring ? ' button-disabled' : '']">I'm sure</button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
