<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Github, Landmark, ArrowRightLeft } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import accounts from '@/routes/accounts';
import transactions from '@/routes/transactions';

import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Transactions',
        href: transactions.index(),
        icon: ArrowRightLeft,
    },
    {
        title: 'Accounts',
        href: accounts.index(),
        icon: Landmark,
    }
];

const footerNavItems: NavItem[] = [
   {
        title: 'Github',
        href: 'https://github.com/gustavocoimbradev/your-money',
        icon: Github,
    }
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="transactions.index()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
