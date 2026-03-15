<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue'; 
import route from '@/routes/transactions'; 
import type { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
import { ArrowLeft, ArrowRight, Circle, CircleCheckBig } from 'lucide-vue-next';
import { onMounted } from 'vue';
const { formatMoney } = useCurrency();

interface Account {
    title: string;
}
interface Transaction {
    id: number;
    title: string;
    description: string;
    account: Account;
    value: number;
    reference: string;
    paid: boolean;
}
const props = defineProps<{
    transactions: Transaction[],
    archived_transactions: Transaction[],
    account_balance: number,
    current_balance: number,
}>();
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Transactions',
        href: route.index(),
    }
]; 
const currentMonth = ref(String((new Date()).getMonth() + 1).padStart(2, '0'));
const currentYear = ref((new Date()).getFullYear());
const filteredTransactions = ref<typeof props.transactions>([]);
const projectedBalance = ref(0);
onMounted(() => {
    const searchPattern = `${currentYear.value}-${currentMonth.value}`;
    filteredTransactions.value = props.transactions.filter(t => 
        t.reference.startsWith(searchPattern)
    );

    projectedBalance.value = filteredTransactions.value.reduce((acc, item) => acc + Number(item.value), 0);

});

const updateFilter = () => {
    const searchPattern = `${currentYear.value}-${currentMonth.value}`;
    filteredTransactions.value = props.transactions.filter(t => 
        t.reference.startsWith(searchPattern)
    );
};

const next = () => {
    let month = parseInt(currentMonth.value) + 1;
    if (month > 12) {
        month = 1;
        currentYear.value++;
    }
    currentMonth.value = String(month).padStart(2, '0');
    updateFilter();
};

const prev = () => {
    let month = parseInt(currentMonth.value) - 1;
    if (month < 1) {
        month = 12;
        currentYear.value--;
    }
    currentMonth.value = String(month).padStart(2, '0');
    updateFilter();
};
const togglePaidForm = useForm();
const togglePaid = (transaction: Transaction) => {
    transaction.paid = !transaction.paid;
    togglePaidForm.put(route.togglePaid(transaction), {
        preserveScroll: true,
        onError: () => transaction.paid = !transaction.paid
    });
}
</script>

<template>
    <Head title="Transactions" />

    <AppLayout :breadcrumbs="breadcrumbs"> 

        <template #header-actions>
            <Link :href="route.create().url" class="button button-dark">
                New transaction
            </Link>
        </template>


        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >

            <div class="flex justify-center gap-4">
                <button class="cursor-pointer" @click="prev">
                    <ArrowLeft class="size-5"/>
                </button>
                <div class="text-lg font-medium">
                    <span>{{ currentMonth }}</span>/<span>{{ currentYear }}</span>
                </div>
                <button class="cursor-pointer" @click="next">
                    <ArrowRight class="size-5"/>
                </button>
            </div>

            <div class="shadow-lg shadow-gray-100 p-4 rounded-xl px-6 pt-0 pb-4 whitespace-nowrap text-left">
                <table class="w-full border-separate border-spacing-y-2 lg:border-collapse lg:border-spacing-y-0" v-if="filteredTransactions.length">
                    <thead class="hidden lg:table-header-group">
                        <tr class="border-b border-gray-200/50 [&>th]:py-3 [&>th]:px-4 [&>th]:text-left [&>th]:text-xs [&>th]:font-semibold [&>th]:text-gray-400 [&>th]:uppercase">
                            <th></th>
                            <th>Reference</th>
                            <th>Title</th>
                            <th>Value</th>
                            <th>Account</th>
                            <th></th>
                        </tr>
                    </thead>
                    
                    <tbody class="flex flex-col w-full lg:table-row-group">
                        <tr v-for="transaction in filteredTransactions" :key="transaction.id" 
                            class="group w-full flex flex-col mb-3 p-4 bg-white border border-gray-100 rounded-2xl shadow-sm 
                                lg:mb-0 lg:p-0 lg:bg-transparent lg:border-0 lg:border-b lg:border-gray-200/50 lg:rounded-none lg:shadow-none lg:table-row 
                                    transition-all lg:cursor-default"
                            @click="window.innerWidth < 1024 ? $inertia.visit(route('transactions.edit', transaction.id)) : null">
                            
                            <td class="flex justify-between items-center py-1 lg:table-cell lg:py-4 lg:px-4 lg:w-[1px]">
                                <span class="text-[10px] font-bold uppercase text-gray-400 lg:hidden">Status</span>
                                <button @click.stop.prevent="togglePaid(transaction)" class="cursor-pointer p-1 -m-1">
                                    <CircleCheckBig class="size-5 text-green-600" v-if="transaction.paid"/>
                                    <Circle class="size-5 text-gray-300" v-else/>
                                </button>
                            </td>

                            <td class="flex justify-between items-center py-1 lg:table-cell lg:py-4 lg:px-4 lg:text-gray-600">
                                <span class="text-[10px] font-bold uppercase text-gray-400 lg:hidden">Reference</span>
                                <span class="text-sm font-medium">
                                    {{ new Date(transaction.reference + 'T12:00:00').toLocaleDateString() }}
                                </span>
                            </td>

                            <td class="flex justify-between items-center py-1 lg:table-cell lg:py-4 lg:px-4">
                                <span class="text-[10px] font-bold uppercase text-gray-400 lg:hidden">Title</span>
                                <span class="text-sm font-semibold text-gray-900 lg:font-medium lg:text-gray-600">
                                    {{ transaction.title }}
                                </span>
                            </td>

                            <td class="flex justify-between items-center py-1 lg:table-cell lg:py-4 lg:px-4">
                                <span class="text-[10px] font-bold uppercase text-gray-400 lg:hidden">Value</span>
                                <span :class="[transaction.value < 0 ? 'text-red-600' : 'text-green-600', 'text-sm font-bold']">
                                    {{ formatMoney(transaction.value) }}
                                </span>
                            </td>

                            <td class="flex justify-between items-center py-1 lg:table-cell lg:py-4 lg:px-4">
                                <span class="text-[10px] font-bold uppercase text-gray-400 lg:hidden">Account</span>
                                <div class="flex items-center gap-2">
                                    <div class="size-2 rounded-full bg-blue-500 lg:hidden"></div>
                                    <span class="text-sm text-gray-500">{{ transaction.account.title }}</span>
                                </div>
                            </td>

                            <td class="mt-4 pt-3 border-t border-gray-50 lg:mt-0 lg:pt-0 lg:border-0 lg:table-cell lg:text-right">
                                <Link :href="route.edit(transaction.id)" 
                                    class="flex items-center justify-center gap-2 w-full py-2 bg-gray-50 rounded-xl text-gray-600 
                                            lg:bg-transparent lg:w-auto lg:py-0 lg:opacity-0 lg:group-hover:opacity-100 lg:group-hover:-translate-x-2 transition-all">
                                    <span class="text-xs font-bold uppercase lg:normal-case lg:font-medium">Edit</span>
                                    <ArrowRight class="size-4"/>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else class="text-center mb-12">
                    No transactions found
                </div>
                <div class="flex flex-col lg:flex-row gap-5 lg:gap-12 mt-4 mb-2 justify-end">
                    <div class="min-w-[150px]">
                        <small class="text-md">Current balance</small>
                        <h4 :class="['text-xl font-medium mt-1', current_balance >= 0 ? 'text-green-600' : 'text-red-600']">{{ formatMoney(current_balance) }}</h4>
                    </div>
                    <div class="min-w-[150px]">
                        <small class="text-md">Projected balance</small>
                        <h4 :class="['text-xl font-medium mt-1', projectedBalance >= 0 ? 'text-green-600' : 'text-red-600']">{{ formatMoney(projectedBalance) }}</h4>
                    </div>
                </div>
            </div>

            
        </div>
    </AppLayout>
</template>
